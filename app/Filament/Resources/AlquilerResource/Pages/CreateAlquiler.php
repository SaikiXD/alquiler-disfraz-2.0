<?php

namespace App\Filament\Resources\AlquilerResource\Pages;

use App\Enums\DisfrazPiezaEnum;
use App\Filament\Resources\AlquilerResource;
use App\Models\Alquiler;
use App\Models\AlquilerDisfraz;
use App\Models\AlquilerDisfrazPieza;
use App\Models\DisfrazPieza;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAlquiler extends CreateRecord
{
    protected static string $resource = AlquilerResource::class;

    protected function afterCreate(): void
    {
        $alquiler = $this->record->load('alquilerDisfrazs'); // Obtener el pedido recién creado

        foreach ($alquiler->alquilerDisfrazs as $alquilerDisfraz) {
            // Obtener la cantidad de disfraces alquilados
            $cantidadDisfraces = $alquilerDisfraz->cantidad;
            // Obtener las piezas seleccionadas en el CheckboxList
            $piezasSeleccionadas = $alquilerDisfraz->piezas_seleccionadas;
            foreach ($piezasSeleccionadas as $pieza_id) {
                // Obtener el stock disponible de la pieza
                $disfrazPiezaReservado = DisfrazPieza::where('disfraz_id', $alquilerDisfraz->disfraz_id)
                    ->where('pieza_id', $pieza_id)
                    ->where('status', 'reservado') // Verifica el estado ('reservado' o 'alquilado')
                    ->first();
                $disfrazPiezadisponible = DisfrazPieza::where('disfraz_id', $alquilerDisfraz->disfraz_id)
                    ->where('pieza_id', $pieza_id)
                    ->where('status', 'disponible') // Verifica el estado ('reservado' o 'alquilado')
                    ->first();
                $stockDisponible = $disfrazPiezadisponible->stock;
                $cantidadPieza = min($cantidadDisfraces, $stockDisponible);

                $stockUpdate = $stockDisponible - $cantidadPieza;
                $disfrazPiezadisponible->update([
                    'stock' => $stockUpdate,
                ]);
                $stockDisponible1 = $disfrazPiezaReservado->stock; //0
                // Determinar la cantidad a registrar (mínimo entre cantidad de disfraces y stock disponible)
                $stockUpdate1 = $stockDisponible1 + $cantidadPieza;
                $disfrazPiezaReservado->update([
                    'stock' => $stockUpdate1, // Actualizamos la cantidad
                ]);
                AlquilerDisfrazPieza::create([
                    'alquiler_disfraz_id' => $alquilerDisfraz->id,
                    'pieza_id' => $pieza_id,
                    'cantidad_reservada' => $cantidadPieza,
                ]);
            }
        }
    }
}
