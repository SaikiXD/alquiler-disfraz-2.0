<?php

namespace App\Filament\Pages;

use App\Models\Alquiler;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Enums\AlquilerStatusEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Response;

class ReporteAlquileres extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationGroup = 'Reportes';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.reporte-alquileres';

    public function table(Table $table): Table
    {
        return $table
            ->query(Alquiler::query()->with(['cliente', 'alquilerDisfrazs.disfraz']))
            ->columns([
                Tables\Columns\TextColumn::make('cliente.name')->label('Cliente')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('alquilerDisfrazs')
                    ->label('Trajes')
                    ->getStateUsing(function ($record) {
                        return $record->alquilerDisfrazs->map(fn($item) => $item->disfraz->nombre ?? '—')->join(', ');
                    }),
                Tables\Columns\TextColumn::make('fecha_alquiler')->date()->label('Inicio'),
                Tables\Columns\TextColumn::make('fecha_devolucion')->date()->label('Devolución'),
                Tables\Columns\TextColumn::make('total')->label('Monto')->money('BOB', locale: 'es_BO'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(
                        fn($state) => $state instanceof AlquilerStatusEnum ? $state->name : (string) $state
                    )
                    ->sortable(),
            ])
            ->filters(
                [
                    SelectFilter::make('status')->label('Estado')->options(AlquilerStatusEnum::class)->columnSpan(1),

                    Filter::make('fecha_alquiler')
                        ->label('Rango de Fechas')
                        ->form([DatePicker::make('from')->label('Desde'), DatePicker::make('until')->label('Hasta')])
                        ->query(function ($query, array $data) {
                            return $query
                                ->when($data['from'], fn($q, $date) => $q->whereDate('fecha_alquiler', '>=', $date))
                                ->when($data['until'], fn($q, $date) => $q->whereDate('fecha_alquiler', '<=', $date));
                        })
                        ->columns(2)
                        ->columnSpan(2),
                ],
                layout: FiltersLayout::AboveContent
            )
            ->filtersFormColumns(3);
    }
    protected function getHeaderActions(): array
    {
        return [Action::make('Exportar PDF')->action('exportarPdf')->icon('heroicon-o-arrow-down-tray')];
    }
    public function exportarPdf()
    {
        $data = $this->getFilteredTableQuery()->get();

        $pdf = Pdf::loadView('pdf.reporte-alquileres', ['registros' => $data]);

        return response()->streamDownload(fn() => print $pdf->stream(), 'reporte-alquileres.pdf');
    }
}
