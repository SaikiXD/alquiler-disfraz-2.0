<?php

namespace App\Filament\Widgets;

use App\Models\Alquiler;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimosAlquileres extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Alquiler::query()
                    ->where('status', 'alquilado') // solo los alquileres con estado "alquilado"
                    ->latest('fecha_alquiler')
                    ->with(['cliente', 'alquilerDisfrazs.disfraz'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('cliente.name')->label('Cliente'),
                Tables\Columns\TextColumn::make('fecha_alquiler')->label('Fecha')->date(),
                Tables\Columns\TextColumn::make('status')->label('Estado')->badge(),
                Tables\Columns\TextColumn::make('total')->money('BOB'),
                // ...
            ]);
    }
}
