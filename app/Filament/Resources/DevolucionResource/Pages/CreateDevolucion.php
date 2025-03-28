<?php

namespace App\Filament\Resources\DevolucionResource\Pages;

use App\Enums\AlquilerStatusEnum;
use App\Enums\DisfrazPiezaEnum;
use App\Filament\Resources\DevolucionResource;
use App\Models\Alquiler;
use App\Models\AlquilerDisfrazPieza;
use App\Models\DisfrazPieza;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDevolucion extends CreateRecord
{
    public function getTitle(): string
    {
        return 'Devolución';
    }

    public function getBreadcrumb(): string
    {
        return 'Registrar';
    }
    protected static string $resource = DevolucionResource::class;

    protected function afterCreate(): void
    {
        $devolucion = $this->record; // Obtener la devolución recién creada
        $alquilerId = $devolucion->alquiler_id;

        $alquiler = Alquiler::find($alquilerId);
        $alquiler->update([
            'status' => AlquilerStatusEnum::FINALIZADO, // Actualizamos la cantidad
        ]);
        $piezasDanadasStock = $devolucion->devolucionPiezas()->get()->keyBy('alquiler_disfraz_pieza_id');

        // Obtener todas las piezas alquiladas de ese alquiler
        $todasLasPiezas = AlquilerDisfrazPieza::whereHas('alquilerDisfraz', function ($query) use ($alquilerId) {
            $query->where('alquiler_id', $alquilerId);
        })->get();

        foreach ($todasLasPiezas as $pieza) {
            $piezaDanada = null;
            if (isset($piezasDanadasStock[$pieza->id])) {
                // Si existe, obtener el registro correspondiente
                $piezaDanada = $piezasDanadasStock[$pieza->id];

                // Asignar el valor de cantidad a stockDanados
                $stockDanados = $piezaDanada->cantidad;
            } else {
                // Si no existe, asignar 0
                $stockDanados = 0;
            }
            $piezasEstados = DisfrazPieza::where('disfraz_id', $pieza->alquilerDisfraz->disfraz_id)
                ->where('pieza_id', $pieza->pieza_id)
                ->get()
                ->keyBy(fn($pieza) => $pieza->status->value);

            $disfrazPiezaReservado = $piezasEstados[DisfrazPiezaEnum::RESERVADO->value] ?? null;
            $disfrazPiezaDisponible = $piezasEstados[DisfrazPiezaEnum::DISPONIBLE->value] ?? null;
            $disfrazPiezaDanado = $piezasEstados[DisfrazPiezaEnum::DAÑADO->value] ?? null;
            $disfrazPiezaPerdido = $piezasEstados[DisfrazPiezaEnum::PERDIDO->value] ?? null;

            if ($disfrazPiezaReservado && $disfrazPiezaDisponible) {
                // Mover la cantidad reservada a stock disponible
                //abs valor absoluto
                $cantidadDisponible = $pieza->cantidad_reservada - $stockDanados;
                $cantidadReservado = $pieza->cantidad_reservada;

                $disfrazPiezaDisponible->increment('stock', $cantidadDisponible);
                $disfrazPiezaReservado->decrement('stock', $cantidadReservado);
            }
            if ($piezaDanada !== null) {
                if ($piezaDanada->estado_pieza == 'dañado') {
                    $disfrazPiezaDanado->increment('stock', $stockDanados);
                }
                if ($piezaDanada->estado_pieza == 'perdido') {
                    $disfrazPiezaPerdido->increment('stock', $stockDanados);
                }
            }
            $devolucion->devolucionPiezas()->create([
                'alquiler_disfraz_pieza_id' => $pieza->id,
                'cantidad' => $cantidadDisponible, // o la cantidad devuelta si manejas otra lógica
                'estado_pieza' => 'bueno',
            ]);
        }
    }
}
