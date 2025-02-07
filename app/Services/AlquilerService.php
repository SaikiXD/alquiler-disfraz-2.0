<?php

namespace App\Services;

use App\Enums\AlquilerStatusEnum;
use App\Enums\PiezaStatusEnum;
use App\Models\Alquiler;
use App\Models\DisfrazPieza;
use App\Models\Pieza;
use Illuminate\Support\Facades\DB;

class AlquilerService
{
    public static function obtenerStockMinimoDisfraz($disfrazId)
    {
        if (!$disfrazId) {
            return 0;
        }

        return DisfrazPieza::where('disfraz_id', $disfrazId)->min('stock') ?? 0; // Obtiene la menor cantidad de stock disponible
    }
    public static function reservarPiezas($disfrazId, $cantidad)
    {
        return DB::transaction(function () use ($disfrazId, $cantidad) {
            $piezas = Pieza::whereIn('id', function ($query) use ($disfrazId) {
                $query->select('pieza_id')->from('disfraz_pieza')->where('disfraz_id', $disfrazId);
            })
                ->where('status', 0) // Solo piezas disponibles
                ->limit($cantidad)
                ->get();

            if ($piezas->count() < $cantidad) {
                return false; // No hay suficientes piezas disponibles
            }

            foreach ($piezas as $pieza) {
                $pieza->status = 1; // Reservada
                $pieza->save();
            }

            return true;
        });
    }
}
