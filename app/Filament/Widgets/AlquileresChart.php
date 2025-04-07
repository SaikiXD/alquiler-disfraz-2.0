<?php

namespace App\Filament\Widgets;

use App\Models\Alquiler;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AlquileresChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $data = Trend::model(Alquiler::class)
            ->between(start: now()->startOfYear(), end: now()->endOfYear())
            ->dateColumn('fecha_alquiler')
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Alquileres',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#facc15',
                ],
            ],
            'labels' => $data->map(
                fn(TrendValue $value) => Carbon::parse($value->date)->locale('es')->translatedFormat('F')
            ),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
