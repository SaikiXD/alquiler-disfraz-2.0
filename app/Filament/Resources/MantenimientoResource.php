<?php

namespace App\Filament\Resources;

use App\Enums\DisfrazPiezaStatusEnum;
use App\Filament\Resources\MantenimientoResource\Pages;
use App\Enums\MantenimientoEstadoEnum;
use App\Models\DisfrazPieza;
use App\Models\Mantenimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class MantenimientoResource extends Resource
{
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Mantenimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('devolucionDisfrazPieza.alquilerDisfrazPieza.alquilerDisfraz.disfraz.nombre')
            ->columns([
                TextColumn::make('devolucionDisfrazPieza.alquilerDisfrazPieza.alquilerDisfraz.disfraz.nombre')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('pieza.name')->label('Nombre de la Pieza')->searchable(),
                TextColumn::make('cantidad'),
                Tables\Columns\TextColumn::make('created_at')->label('Inicio')->date()->sortable(),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(
                        fn($state) => $state instanceof MantenimientoEstadoEnum ? $state->name : (string) $state
                    )
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('finalizar')
                    ->label('Finalizar')
                    ->action(function ($record) {
                        $record->update(['estado' => 'completado']);
                        $disfrazPieza = DisfrazPieza::where('pieza_id', $record->pieza_id)->get();
                        foreach ($disfrazPieza as $pieza) {
                            if ($pieza->status === DisfrazPiezaStatusEnum::DISPONIBLE->value) {
                                $pieza->increment('stock', $record->cantidad);
                            }
                            if ($pieza->status === DisfrazPiezaStatusEnum::ALQUILADO->value) {
                                $pieza->decrement('stock', $record->cantidad);
                            }
                        }
                    })
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('finalizar')
                        ->label('Marcar como completado')
                        ->action(function (Collection $records) {
                            foreach ($records as $record) {
                                $record->update(['estado' => 'completado']);
                                $disfrazPiezas = \App\Models\DisfrazPieza::where('pieza_id', $record->pieza_id)->get();
                                foreach ($disfrazPiezas as $pieza) {
                                    if ($pieza->status === \App\Enums\DisfrazPiezaStatusEnum::DISPONIBLE->value) {
                                        $pieza->increment('stock', $record->cantidad);
                                    }
                                    if ($pieza->status === \App\Enums\DisfrazPiezaStatusEnum::ALQUILADO->value) {
                                        $pieza->decrement('stock', $record->cantidad);
                                    }
                                }
                            }
                        })
                        ->color('success')
                        ->icon('heroicon-o-check'),
                ]),
            ]);
    }
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->devolucionDisfrazPieza->alquilerDisfrazPieza->alquilerDisfraz->disfraz->nombre ?? '';
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('estado', '!=', 'completado');
    }
    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMantenimientos::route('/'),
            'create' => Pages\CreateMantenimiento::route('/create'),
            'edit' => Pages\EditMantenimiento::route('/{record}/edit'),
        ];
    }
}
