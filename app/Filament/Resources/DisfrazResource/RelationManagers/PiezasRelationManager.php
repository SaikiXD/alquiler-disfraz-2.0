<?php

namespace App\Filament\Resources\DisfrazResource\RelationManagers;

use App\Enums\DisfrazPiezaEnum;
use App\Models\DisfrazPieza;
use App\Models\Pieza;
use App\Models\Tipo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Symfony\Contracts\Service\Attribute\Required;

class PiezasRelationManager extends RelationManager
{
    protected static string $relationship = 'piezas';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('tipo_id')->options(Tipo::pluck('name', 'id'))->required(),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('stock')->required(),
            Forms\Components\TextInput::make('color')->required(),
            Forms\Components\TextInput::make('size')->required(),
            Forms\Components\TextInput::make('material')->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('stock'),
                Tables\Columns\TextColumn::make('color'),
                Tables\Columns\TextColumn::make('size'),
                Tables\Columns\TextColumn::make('material'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->after(function (Pieza $pieza) {
                    // Crear los tres registros adicionales con estados diferentes
                    $pieza->load('disfrazs');
                    foreach ($pieza->disfrazs as $disfraz) {
                        // Aquí accedes a los atributos del pivote de la relación

                        $piezas = $disfraz->pivot;
                    }
                    $estadosAdicionales = [
                        DisfrazPiezaEnum::RESERVADO->value,
                        DisfrazPiezaEnum::DAÑADO->value,
                        DisfrazPiezaEnum::PERDIDO->value,
                    ];

                    foreach ($estadosAdicionales as $estado) {
                        DisfrazPieza::create([
                            'disfraz_id' => $piezas->disfraz_id,
                            'pieza_id' => $piezas->pieza_id,
                            'stock' => 0,
                            'color' => $piezas->color,
                            'size' => $piezas->size,
                            'material' => $piezas->material,
                            'status' => $estado,
                        ]);
                    }
                }),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }
}
