<?php

namespace App\Filament\Resources\AlquilerResource\Pages;

use App\Enums\DisfrazStatusEnum;
use App\Filament\Resources\AlquilerResource;
use App\Models\AlquilerDisfrazPieza;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlquiler extends EditRecord
{
    protected static string $resource = AlquilerResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
    protected function afterSave(): void
    {
        $alquiler = $this->record->load('alquilerDisfrazs');
        foreach ($alquiler->alquilerDisfrazs as $alquilerDisfraz) {
            $cantidadNueva = $alquilerDisfraz->cantidad;
            $disfraz = Disfraz::find($alquilerDisfraz->disfraz_id);
            $piezasSeleccionadas = $alquilerDisfraz->piezas_seleccionadas;
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
                $disfrazPiezaReservado = DisfrazPieza::where('disfraz_id', $alquilerDisfraz->disfraz_id)
                    ->where('pieza_id', $pieza_id)
                    ->where('status', 'reservado')
                    ->first();

                $disfrazPiezaDisponible = DisfrazPieza::where('disfraz_id', $alquilerDisfraz->disfraz_id)
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
            //aqui cambio el estado del disfraz
            $piezasConStock = $disfraz->disfrazPiezas()->where('stock', '>', 0)->where('status', 'disponible')->count();
            $totalPiezas = $disfraz->disfrazPiezas()->where('status', 'disponible')->count();
            if ($piezasConStock === 0) {
                $disfraz->update([
                    'status' => DisfrazStatusEnum::NO_DISPONIBLE->value,
                ]);
            } elseif ($totalPiezas > $piezasConStock) {
                $disfraz->update([
                    'status' => DisfrazStatusEnum::INCOMPLETO->value,
                ]);
            } else {
                $disfraz->update([
                    'status' => DisfrazStatusEnum::DISPONIBLE->value,
                ]);
            }
        }
    }
}
