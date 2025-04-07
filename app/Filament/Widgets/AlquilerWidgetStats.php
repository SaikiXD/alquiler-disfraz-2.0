<?php

namespace App\Filament\Widgets;

use App\Enums\AlquilerStatusEnum;
use App\Models\Alquiler;
use App\Models\Disfraz;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AlquilerWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Alquilados', $this->getAlquileresActivos()),
            Stat::make('Finalizados', $this->getAlquileresFinalizados()),
        ];
    }
    protected function getAlquileresActivos(): int
    {
        return Alquiler::where('status', AlquilerStatusEnum::ALQUILADO->value)->count();
    }
    protected function getAlquileresFinalizados(): int
    {
        return Alquiler::where('status', AlquilerStatusEnum::FINALIZADO->value)->count();
    }
}
