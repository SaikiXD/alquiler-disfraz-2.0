<?php

namespace App\Filament\Resources\DevolucionResource\Pages;

use App\Enums\AlquilerStatusEnum;
use App\Enums\DisfrazPiezaStatusEnum;
use App\Filament\Resources\DevolucionResource;
use App\Models\Alquiler;
use App\Models\AlquilerDisfraz;
use App\Models\AlquilerDisfrazPieza;
use App\Models\DevolucionDisfrazPieza;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use App\Models\Mantenimiento;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDevolucion extends CreateRecord
{
    public function getTitle(): string
    {
        return 'Devolución';
    }
    protected function getRedirectUrl(): string
    {
        // Redirige automáticamente a la página de edición después de crear el registro
        return \App\Filament\Resources\AlquilerResource::getUrl();
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
        $alquilerDisfrazId = AlquilerDisfraz::Where('alquiler_id', $alquilerId)->pluck('disfraz_id')->toArray();
        $alquiler = Alquiler::find($alquilerId);
        $alquiler->update([
            'status' => AlquilerStatusEnum::FINALIZADO, // Actualizamos la cantidad
        ]);
        $piezasDanadasStock = $devolucion->devolucionPiezas()->get();
        $piezasDanadas = $piezasDanadasStock
            ->where('estado_pieza', 'danado')
            ->groupBy('alquiler_disfraz_pieza_id')
            ->map(function ($items) {
                return (object) [
                    'id' => $items->first()->id,
                    'cantidad' => $items->sum('cantidad'),
                    'alquiler_disfraz_pieza_id' => $items->first()->alquiler_disfraz_pieza_id,
                    'estado' => $items->first()->estado_pieza,
                ];
            })
            ->sortBy('id');
        $piezasPerdidas = $piezasDanadasStock
            ->where('estado_pieza', 'perdido')
            ->groupBy('alquiler_disfraz_pieza_id')
            ->map(function ($items) {
                return (object) [
                    'id' => $items->first()->id,
                    'cantidad' => $items->sum('cantidad'),
                    'alquiler_disfraz_pieza_id' => $items->first()->alquiler_disfraz_pieza_id,
                    'estado' => $items->first()->estado_pieza,
                ];
            })
            ->sortBy('id');
        $todasLasPiezas = AlquilerDisfrazPieza::whereHas('alquilerDisfraz', function ($query) use ($alquilerId) {
            $query->where('alquiler_id', $alquilerId);
        })->get();
        foreach ($todasLasPiezas as $pieza) {
            $cantidadAlquilada = $pieza->cantidad_reservada;
            $piezaDanado = $piezasDanadas->where('alquiler_disfraz_pieza_id', $pieza->id)->first();
            $stockDanado = $piezaDanado?->cantidad ?? 0;
            $piezaPerdida = $piezasPerdidas->where('alquiler_disfraz_pieza_id', $pieza->id)->first();
            $stockperdido = $piezaPerdida?->cantidad ?? 0;
            $stockBueno = $cantidadAlquilada - $stockDanado - $stockperdido;
            if ($stockBueno > 0) {
                $devolucion->devolucionPiezas()->create([
                    'alquiler_disfraz_pieza_id' => $pieza->id,
                    'cantidad' => $stockBueno,
                    'estado_pieza' => 'bueno',
                ]);
            }
            $devolucionpiezaId = DevolucionDisfrazPieza::where('alquiler_disfraz_pieza_id', $pieza->id)->value('id');
            if ($stockDanado > 0) {
                Mantenimiento::create([
                    'devolucion_disfraz_pieza_id' => $devolucionpiezaId,
                    'pieza_id' => $pieza->pieza_id,
                    'estado' => 'reparacion',
                    'cantidad' => $stockDanado,
                ]);
            }
            if ($stockBueno > 0) {
                Mantenimiento::create([
                    'devolucion_disfraz_pieza_id' => $devolucionpiezaId,
                    'pieza_id' => $pieza->pieza_id,
                    'estado' => 'limpieza',
                    'cantidad' => $stockBueno,
                ]);
            }
            if ($stockperdido > 0) {
                $disfrazPieza = DisfrazPieza::where('pieza_id', $pieza->pieza_id)
                    ->where('status', DisfrazPiezaStatusEnum::ALQUILADO->value)
                    ->get();
                foreach ($disfrazPieza as $dP) {
                    $dP->decrement('stock', $stockperdido);
                }
            }
        }
        foreach ($alquilerDisfrazId as $disfraz_id) {
            $disfraz = Disfraz::find($disfraz_id);
            $disfraz->actualizarEstado();
        }
    }
}
