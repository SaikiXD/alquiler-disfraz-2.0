<?php

namespace App\Filament\Resources\AlquilerResource\Pages;

use App\Enums\DisfrazPiezaStatusEnum;
use App\Filament\Resources\AlquilerResource;
use App\Models\AlquilerDisfrazPieza;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateAlquiler extends CreateRecord
{
    protected static string $resource = AlquilerResource::class;
    protected function afterCreate(): void
    {
        DB::transaction(function () {
            //$state = $this->form->getState();
            $alquiler = $this->record->load('alquilerDisfrazs'); // Obtener el pedido recién creado
            foreach ($alquiler->alquilerDisfrazs as $alquilerDisfraz) {
                $cantidadDisfraces = $alquilerDisfraz->cantidad;
                $disfraz = Disfraz::find($alquilerDisfraz->disfraz_id);
                $piezasSeleccionadas = $alquilerDisfraz->piezas_seleccionadas;
                foreach ($piezasSeleccionadas as $pieza_id) {
                    $alquilado = DisfrazPieza::where('pieza_id', $pieza_id)
                        ->where('status', DisfrazPiezaStatusEnum::ALQUILADO->value)
                        ->get();
                    $disponible = DisfrazPieza::where('pieza_id', $pieza_id)
                        ->where('status', DisfrazPiezaStatusEnum::DISPONIBLE->value)
                        ->get();
                    $stockDisponible = $disponible->first()?->stock ?? 0;
                    $cantidadReservada = min($cantidadDisfraces, $stockDisponible);
                    foreach ($disponible as $item) {
                        $item->decrement('stock', $cantidadReservada);
                    }
                    foreach ($alquilado as $item) {
                        $item->increment('stock', $cantidadReservada);
                    }
                    AlquilerDisfrazPieza::create([
                        'alquiler_disfraz_id' => $alquilerDisfraz->id,
                        'pieza_id' => $pieza_id,
                        'cantidad_reservada' => $cantidadReservada,
                    ]);
                }
                $disfraz->actualizarEstado();
            }
        });
    }
}
