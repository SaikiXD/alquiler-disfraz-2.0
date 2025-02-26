<?php

namespace App\Filament\Resources\AlquilerResource\Pages;

use App\Filament\Resources\AlquilerResource;
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
            $piezasSeleccionadas = $alquilerDisfraz->piezas_seleccionadas;
            $cantidadAnterior = [];
            foreach ($piezasSeleccionadas as $pieza_id) {
                // Devolver stock de la versión anterior
                $cantidadAnterior = $alquilerDisfraz->piezas_reservadas[$pieza_id];
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
            }
        }
    }
}
