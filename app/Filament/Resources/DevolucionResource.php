<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DevolucionResource\Pages;
use App\Filament\Resources\DevolucionResource\RelationManagers;
use App\Models\Devolucion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DevolucionResource extends Resource
{
    protected static ?string $model = Devolucion::class;
    protected static bool $navigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('alquiler_id')
                ->default(fn($get) => request()->query('alquiler_id')) // Obtén el ID del alquiler desde la URL
                ->required()
                ->numeric()
                ->disabled(), // Deshabilitar para que no se pueda editar

            // Campo para mostrar el nombre del cliente relacionado
            Forms\Components\TextInput::make('cliente_name')
                ->label('Nombre del Cliente')
                ->default(fn($get) => optional(Devolucion::find($get('alquiler_id')))->alquiler->cliente->name)
                ->disabled(), // Deshabilitar para que no se pueda editar

            Forms\Components\DateTimePicker::make('fecha_devolucion_real')->required(),
            Forms\Components\TextInput::make('multa')->required()->numeric()->default(0.0),
            Forms\Components\TextInput::make('estado')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alquiler_id')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('fecha_devolucion_real')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('multa')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('estado'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
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
            'index' => Pages\ListDevolucions::route('/'),
            'create' => Pages\CreateDevolucion::route('/create'),
            'edit' => Pages\EditDevolucion::route('/{record}/edit'),
        ];
    }
}
