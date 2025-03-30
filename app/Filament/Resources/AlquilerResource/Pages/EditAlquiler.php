<?php

namespace App\Filament\Resources\AlquilerResource\Pages;

use App\Enums\DisfrazStatusEnum;
use App\Filament\Resources\AlquilerResource;
use App\Models\AlquilerDisfraz;
use App\Models\AlquilerDisfrazPieza;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditAlquiler extends EditRecord
{
    protected static string $resource = AlquilerResource::class;

    protected array $disfrazIdsAntes = [];
    protected function beforeSave(): void
    {
        $this->disfrazIdsAntes = $this->record->alquilerDisfrazs()->pluck('disfraz_id')->toArray();
    }
    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
    protected function afterSave(): void
    {
        $alquiler = $this->record->load('alquilerDisfrazs');
        $disfrazIdsActuales = $alquiler->alquilerDisfrazs->pluck('disfraz_id')->toArray();
        $disfrazIdsEliminados = array_diff($this->disfrazIdsAntes, $disfrazIdsActuales);
        foreach ($disfrazIdsEliminados as $disfraz_id) {
            $alquilerDisfraz = AlquilerDisfraz::where('disfraz_id', $disfraz_id)
                ->where('alquiler_id', $alquiler->id)
                ->first();
            if ($alquilerDisfraz) {
                $cantidadReservada = $alquilerDisfraz->cantidad;
                $piezasDisfraz = $alquilerDisfraz->piezas_seleccionadas;
                foreach ($piezasDisfraz as $id_pieza) {
                    $piezaReservada = DisfrazPieza::where('pieza_id', $id_pieza)->where('status', 'reservado')->get();
                    $stockReservado = $alquilerDisfraz->alquilerPiezas
                        ->where('pieza_id', $id_pieza)
                        ->value('cantidad_reservada');
                    $reservado = min($cantidadReservada, $stockReservado);
                    $piezaDisponible = DisfrazPieza::where('pieza_id', $id_pieza)->where('status', 'disponible')->get();
                    if ($piezaReservada && $piezaDisponible) {
                        foreach ($piezaReservada as $item) {
                            $item->decrement('stock', $reservado);
                        }
                        foreach ($piezaDisponible as $item) {
                            $item->increment('stock', $reservado);
                        }
                    }
                }
            }
        }
        $disfrazIdAntes = $this->disfrazIdsAntes;
        $cont = 0;
        foreach ($alquiler->alquilerDisfrazs as $alquilerDisfraz) {
            $disfrazantes = $disfrazIdAntes[$cont];
            $cantidadNueva = $alquilerDisfraz->cantidad;
            $disfrazId = $alquilerDisfraz->disfraz_id;
            $disfraz = Disfraz::find($disfrazId);
            $piezaIds = $disfraz->disfrazpiezas()->pluck('id')->toArray();
            $piezasSeleccionadas = $alquilerDisfraz->piezas_seleccionadas;
            if ($alquilerDisfraz->modo_alquiler === 'completo') {
                if (empty($piezasSeleccionadas) || $disfrazantes != $disfrazId) {
                    $piezas = DisfrazPieza::where('disfraz_id', $disfrazId)
                        ->where('status', 'disponible')
                        ->pluck('pieza_id')
                        ->toArray();
                    $alquilerDisfraz->update([
                        'piezas_seleccionadas' => $piezas,
                    ]);
                    $piezasSeleccionadas = $piezas;
                }
            }
            $piezasAntiguas = AlquilerDisfrazPieza::where('alquiler_disfraz_id', $alquilerDisfraz->id)
                ->pluck('pieza_id')
                ->toArray();
            // Determinar las piezas que han sido deseleccionadas
            $piezasEliminadas = array_diff($piezasAntiguas, $piezasSeleccionadas);

            // Restaurar stock de las piezas eliminadas
            foreach ($piezasEliminadas as $pieza_id) {
                $alquilerDisfrazPieza = AlquilerDisfrazPieza::where('alquiler_disfraz_id', $alquilerDisfraz->id)
                    ->where('pieza_id', $pieza_id)
                    ->first();

                if ($alquilerDisfrazPieza) {
                    $cantidadReservada = $alquilerDisfrazPieza->cantidad_reservada;

                    $disfrazPiezaReservado = DisfrazPieza::where('disfraz_id', $alquilerDisfraz->disfraz_id)
                        ->where('pieza_id', $pieza_id)
                        ->where('status', 'reservado')
                        ->first();

                    $disfrazPiezaDisponible = DisfrazPieza::where('disfraz_id', $alquilerDisfraz->disfraz_id)
                        ->where('pieza_id', $pieza_id)
                        ->where('status', 'disponible')
                        ->first();

                    if ($disfrazPiezaReservado && $disfrazPiezaDisponible) {
                        // Devolver stock de la pieza eliminada
                        $disfrazPiezaDisponible->update([
                            'stock' => $disfrazPiezaDisponible->stock + $cantidadReservada,
                        ]);
                        $disfrazPiezaReservado->update([
                            'stock' => $disfrazPiezaReservado->stock - $cantidadReservada,
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
