<?php

namespace App\Filament\Resources\AlquilerResource\Pages;

use App\Enums\AlquilerStatusEnum;
use App\Enums\DisfrazPiezaEnum;
use App\Enums\DisfrazStatusEnum;
use App\Filament\Resources\AlquilerResource;
use App\Models\AlquilerDisfraz;
use App\Models\AlquilerDisfrazPieza;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class EditAlquiler extends EditRecord
{
    protected static string $resource = AlquilerResource::class;

    protected Collection $disfrazAntes;
    protected function beforeSave(): void
    {
        $this->disfrazAntes = $this->record->alquilerDisfrazs()->with('alquilerPiezas')->get();
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancelar')
                ->label('Cancelar Alquiler')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->action(function () {
                    $alquiler = $this->record->load('alquilerDisfrazs');
                    $disfrazIds = $alquiler->alquilerDisfrazs->pluck('disfraz_id')->toArray();
                    foreach ($alquiler->alquilerDisfrazs as $disfraz) {
                        $piezasDisfraz = $disfraz->piezas_seleccionadas ?? [];
                        foreach ($piezasDisfraz as $id_pieza) {
                            $stockReservado = AlquilerDisfrazPieza::where('alquiler_disfraz_id', $disfraz->id)
                                ->Where('pieza_id', $id_pieza)
                                ->value('cantidad_reservada');
                            $piezaDisponible = DisfrazPieza::where('pieza_id', $id_pieza)
                                ->where('status', DisfrazPiezaEnum::DISPONIBLE->value)
                                ->get();
                            $piezaReservada = DisfrazPieza::where('pieza_id', $id_pieza)
                                ->where('status', DisfrazPiezaEnum::RESERVADO->value)
                                ->get();
                            foreach ($piezaReservada as $item) {
                                $item->decrement('stock', $stockReservado);
                            }
                            foreach ($piezaDisponible as $item) {
                                $item->increment('stock', $stockReservado);
                            }
                        }
                        $disfrazEstado = Disfraz::find($disfraz->disfraz_id);
                        $disfrazEstado->actualizarEstado();
                    }
                    $this->record->update([
                        'status' => AlquilerStatusEnum::CANCELADO, // Asegúrate de tener este estado
                    ]);
                    Notification::make()->title('Alquiler cancelado')->success()->send();
                }),
        ];
    }
    protected function afterSave(): void
    {
        $alquiler = $this->record->load('alquilerDisfrazs');
        //aqui restauro stock del disfraz eliminado
        $disfrazIdsActuales = $alquiler->alquilerDisfrazs->pluck('disfraz_id')->toArray();
        $idsAnteriores = $this->disfrazAntes->pluck('disfraz_id')->toArray();
        $disfrazIdsEliminados = array_diff($idsAnteriores, $disfrazIdsActuales);
        foreach ($disfrazIdsEliminados as $disfraz_id) {
            $disfrazEliminado = $this->disfrazAntes->firstWhere('disfraz_id', $disfraz_id);
            $piezasDisfraz = $disfrazEliminado->piezas_seleccionadas ?? [];
            foreach ($piezasDisfraz as $id_pieza) {
                $stockReservado = $disfrazEliminado->alquilerPiezas
                    ->where('pieza_id', $id_pieza)
                    ->value('cantidad_reservada');
                $piezaDisponible = DisfrazPieza::where('pieza_id', $id_pieza)
                    ->where('status', DisfrazPiezaEnum::DISPONIBLE->value)
                    ->get();
                $piezaReservada = DisfrazPieza::where('pieza_id', $id_pieza)
                    ->where('status', DisfrazPiezaEnum::RESERVADO->value)
                    ->get();
                foreach ($piezaReservada as $item) {
                    $item->decrement('stock', $stockReservado);
                }
                foreach ($piezaDisponible as $item) {
                    $item->increment('stock', $stockReservado);
                }
            }
        }
        //recorro los disfraces guardados
        foreach ($alquiler->alquilerDisfrazs as $alquilerDisfraz) {
            $cantidadNueva = $alquilerDisfraz->cantidad;
            $disfrazId = $alquilerDisfraz->disfraz_id;
            $disfraz = Disfraz::find($disfrazId);
            $piezasSeleccionadas = $alquilerDisfraz->piezas_seleccionadas;
            if (empty($piezasSeleccionadas)) {
                $piezas = DisfrazPieza::where('disfraz_id', $disfrazId)
                    ->where('status', DisfrazPiezaEnum::DISPONIBLE->value)
                    ->pluck('pieza_id')
                    ->toArray();
                $alquilerDisfraz->update([
                    'piezas_seleccionadas' => $piezas,
                ]);
                $piezasSeleccionadas = $piezas;
            }
            $piezasAntiguas = AlquilerDisfrazPieza::where('alquiler_disfraz_id', $alquilerDisfraz->id)
                ->pluck('pieza_id')
                ->toArray();
            // Determinar las piezas que han sido deseleccionadas (Esto es para modo por pieza)
            $piezasEliminadas = array_diff($piezasAntiguas, $piezasSeleccionadas);
            // Restaurar stock de las piezas eliminadas
            foreach ($piezasEliminadas as $pieza_id) {
                $alquilerDisfrazPieza = AlquilerDisfrazPieza::where('alquiler_disfraz_id', $alquilerDisfraz->id)
                    ->where('pieza_id', $pieza_id)
                    ->first();
                if ($alquilerDisfrazPieza) {
                    $cantidadReservada = $alquilerDisfrazPieza->cantidad_reservada;
                    $disfrazPiezaReservado = DisfrazPieza::where('disfraz_id', $disfrazId)
                        ->where('pieza_id', $pieza_id)
                        ->where('status', DisfrazPiezaEnum::RESERVADO->value)
                        ->first();
                    $disfrazPiezaDisponible = DisfrazPieza::where('disfraz_id', $disfrazId)
                        ->where('pieza_id', $pieza_id)
                        ->where('status', DisfrazPiezaEnum::DISPONIBLE->value)
                        ->first();
                    if ($disfrazPiezaReservado && $disfrazPiezaDisponible) {
                        // Devolver stock de la pieza eliminada
                        $disfrazPiezaDisponible->increment([
                            'stock' => $cantidadReservada,
                        ]);
                        $disfrazPiezaReservado->decrement([
                            'stock' => $cantidadReservada,
                        ]);
                    }
                    // Eliminar el registro de la pieza eliminada
                    $alquilerDisfrazPieza->delete();
                }
            }
            foreach ($piezasSeleccionadas as $pieza_id) {
                // Devolver stock de la versión anterior
                $alquilerDisfrazPieza = AlquilerDisfrazPieza::where('alquiler_disfraz_id', $alquilerDisfraz->id)
                    ->where('pieza_id', $pieza_id)
                    ->first();
                $cantidadAnterior = $alquilerDisfrazPieza ? $alquilerDisfrazPieza->cantidad_reservada : 0;
                $disfrazPiezaReservado = DisfrazPieza::where('disfraz_id', $disfrazId)
                    ->where('pieza_id', $pieza_id)
                    ->where('status', 'reservado')
                    ->first();

                $disfrazPiezaDisponible = DisfrazPieza::where('disfraz_id', $disfrazId)
                    ->where('pieza_id', $pieza_id)
                    ->where('status', 'disponible')
                    ->first();
                if ($disfrazPiezaReservado && $disfrazPiezaDisponible) {
                    // Devolver stock anterior
                    $disfrazPiezaDisponible->update([
                        'stock' => $disfrazPiezaDisponible->stock + $cantidadAnterior,
                    ]);
                    $disfrazPiezaReservado->update([
                        'stock' => $disfrazPiezaReservado->stock - $cantidadAnterior,
                    ]);
                }
                // Aplicar nueva reserva
                $stockDisponible = $disfrazPiezaDisponible->stock;
                $nuevoStock = min($cantidadNueva, $stockDisponible);
                $stockUpdate = $stockDisponible - $nuevoStock;
                $disfrazPiezaDisponible->update([
                    'stock' => $stockUpdate,
                ]);
                $stockDisponible1 = $disfrazPiezaReservado->stock;
                $stockUpdate1 = $stockDisponible1 + $nuevoStock;
                $disfrazPiezaReservado->update([
                    'stock' => $stockUpdate1,
                ]);
                if (!$alquilerDisfrazPieza) {
                    // Crear nuevo registro si no existe
                    AlquilerDisfrazPieza::create([
                        'alquiler_disfraz_id' => $alquilerDisfraz->id,
                        'pieza_id' => $pieza_id,
                        'cantidad_reservada' => $nuevoStock,
                    ]);
                } else {
                    // Actualizar el registro existente
                    $alquilerDisfrazPieza->update([
                        'cantidad_reservada' => $nuevoStock,
                    ]);
                }
            }
            $disfraz->actualizarEstado();
        }
    }
}
