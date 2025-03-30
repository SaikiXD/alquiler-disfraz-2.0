<?php

namespace App\Filament\Resources\DisfrazResource\RelationManagers;

use App\Enums\DisfrazPiezaEnum;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use App\Models\Pieza;
use App\Models\Tipo;
use Filament\Actions\ReplicateAction;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Symfony\Contracts\Service\Attribute\Required;

class PiezasRelationManager extends RelationManager
{
    protected static string $relationship = 'disfrazPiezas';

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Pieza')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('pieza_name') // Cambiar el nombre para evitar conflicto
                        ->label('Nombre de la pieza')
                        ->maxLength(255)
                        ->required()
                        ->afterStateHydrated(
                            fn(Forms\Components\TextInput $component, $state, $record) => $component->state(
                                $state ??= $record?->pieza?->name
                            )
                        ),
                    Forms\Components\Select::make('tipo_id')
                        ->label('Tipo de Pieza')
                        ->options(Tipo::pluck('name', 'id'))
                        ->required()
                        ->afterStateHydrated(
                            fn(Forms\Components\Select $component, $state, $record) => $component->state(
                                $state ??= $record?->pieza?->tipo_id
                            )
                        ),
                ]),
            Section::make('Detalles Pieza')->schema([
                Fieldset::make('Características de la Pieza')
                    ->schema([
                        Forms\Components\Hidden::make('pieza_id'),
                        ColorPicker::make('color')->label('Color')->required()->default('#FFFFFF'),
                        Forms\Components\Select::make('size')
                            ->label('Talla')
                            ->options([
                                'XS' => 'XS',
                                'S' => 'S',
                                'M' => 'M',
                                'L' => 'L',
                                'XL' => 'XL',
                                'XXL' => 'XXL',
                                'unica' => 'Unica',
                                'ajustable' => 'Ajustable',
                                'no_aplica' => 'No Aplica',
                            ])
                            ->placeholder('Seleccionar Talla')

                            ->required(),
                        Forms\Components\TextInput::make('material')->required(),
                        Forms\Components\Select::make('gender')
                            ->label('Género')
                            ->options([
                                'masculino' => 'Masculino',
                                'femenino' => 'Femenino',
                                'unisex' => 'Unisex',
                            ])
                            ->placeholder('Seleccionar género')
                            ->required(),
                    ])
                    ->columns(4),
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('stock')->label('Cantidad')->numeric()->minValue(0)->required(),
                    Forms\Components\TextInput::make('price')
                        ->label('Precio')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->prefix('Bs')
                        ->step(0.01),
                ]),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('pieza.name')->label('Pieza'),
                Tables\Columns\TextColumn::make('stock'),
                ColorColumn::make('color') // Muestra el cuadro de color
                    ->label('Color')
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('size')->label('Talla'),
                Tables\Columns\TextColumn::make('material'),
                Tables\Columns\TextColumn::make('gender')->label('Genero'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado') // Agregar la columna de estado
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        DisfrazPiezaEnum::DISPONIBLE->value => 'Disponible',
                        DisfrazPiezaEnum::RESERVADO->value => 'Reservado',
                        DisfrazPiezaEnum::DAÑADO->value => 'Dañado',
                        DisfrazPiezaEnum::PERDIDO->value => 'Perdido',
                    ]) // Obtiene las opciones del Enum
                    ->attribute('status')
                    ->default([DisfrazPiezaEnum::DISPONIBLE->value]),
            ])
            ->headerActions([
                Action::make('replicate')
                    ->label('Asociar Pieza')
                    ->color('success')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Forms\Components\Select::make('pieza_id')
                            ->label('Seleccionar Pieza')
                            ->options(
                                Pieza::query()
                                    ->whereNotIn(
                                        'id',
                                        DisfrazPieza::where('disfraz_id', $this->getOwnerRecord()->id)->pluck(
                                            'pieza_id'
                                        )
                                    )
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Obtener el registro padre (el disfraz actual del RelationManager)
                        $disfraz = $this->getOwnerRecord();
                        $piezaId = $data['pieza_id'];
                        // Buscar la pieza seleccionada
                        foreach (DisfrazPiezaEnum::cases() as $status) {
                            $piezaOriginal = DisfrazPieza::where('status', $status->value)
                                ->where('pieza_id', $piezaId)
                                ->first();
                            // Crear una réplica de la pieza seleccionada
                            $newPieza = $piezaOriginal->replicate();

                            // Asociar la nueva réplica al disfraz actual
                            $newPieza->disfraz_id = $disfraz->id;

                            // Guardar la réplica
                            $newPieza->save();
                        }
                    })
                    ->after(function ($data) {
                        $disfraz = $this->getOwnerRecord();

                        $pieza = DisfrazPieza::where('pieza_id', $data['pieza_id'])->first();

                        $precioPieza = round($pieza->price * 0.1, 2);
                        $disfrazprecio = round($disfraz->price + $precioPieza, 2);

                        $disfraz->update([
                            'price' => $disfrazprecio,
                        ]);
                    })
                    ->successNotificationTitle('Pieza asociada exitosamente'),

                Tables\Actions\CreateAction::make()
                    ->label('Agregar Nueva Pieza')
                    ->icon('heroicon-o-plus')
                    ->form(fn(Form $form) => $this->form($form)) // ✅ Usa el formulario definido
                    ->modalSubmitActionLabel('Guardar Pieza')
                    ->createAnother(false)
                    ->mutateFormDataUsing(function (array $data) {
                        $pieza = \App\Models\Pieza::create([
                            'tipo_id' => $data['tipo_id'],
                            'name' => $data['pieza_name'],
                        ]);

                        $data['pieza_id'] = $pieza->id;

                        unset($data['tipo_id'], $data['pieza_name']);

                        return $data;
                    })
                    ->after(function ($record) {
                        // Crear los tres registros adicionales con estados diferentes
                        $estadosAdicionales = [
                            DisfrazPiezaEnum::RESERVADO->value,
                            DisfrazPiezaEnum::DAÑADO->value,
                            DisfrazPiezaEnum::PERDIDO->value,
                        ];

                        foreach ($estadosAdicionales as $estado) {
                            DisfrazPieza::create([
                                'disfraz_id' => $record->disfraz_id,
                                'pieza_id' => $record->pieza_id,
                                'stock' => 0,
                                'price' => $record->price,
                                'color' => $record->color,
                                'size' => $record->size,
                                'material' => $record->material,
                                'gender' => $record->gender,
                                'status' => $estado,
                            ]);
                        }
                        //asignar precio a disfraz
                        $disfraz = Disfraz::where('id', $record->disfraz_id)->first();
                        $precioPieza = round($record->price * 0.1, 2);
                        $disfrazprecio = round($disfraz->price + $precioPieza, 2);
                        $disfraz->update([
                            'price' => $disfrazprecio,
                        ]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function ($record) {
                        $precioPiezaAnterior = round($record->getOriginal('price') * 0.2, 2);
                        $disfraz = Disfraz::find($record->disfraz_id);
                        if ($disfraz) {
                            $disfrazprecio = round($disfraz->price - $precioPiezaAnterior, 2);
                            $disfraz->update([
                                'price' => $disfrazprecio,
                            ]);
                        }
                    })
                    ->after(function ($record) {
                        $precioPieza = round($record->price * 0.2, 2);
                        $disfraz = Disfraz::find($record->disfraz_id);
                        if ($disfraz) {
                            $disfrazprecio = round($disfraz->price + $precioPieza, 2);
                            $disfraz->update([
                                'price' => $disfrazprecio,
                            ]);
                        }
                    })
                    ->mutateFormDataUsing(function (array $data) {
                        // Buscar la pieza relacionada
                        $pieza = \App\Models\Pieza::find($data['pieza_id']);

                        if ($pieza) {
                            $pieza->update([
                                'tipo_id' => $data['tipo_id'], // Actualiza el tipo
                                'name' => $data['pieza_name'], // Actualiza el nombre editado
                            ]);
                        }

                        // Limpiar datos para evitar conflicto
                        unset($data['tipo_id'], $data['pieza_name']);

                        return $data;
                    }),

                Tables\Actions\DeleteAction::make()->before(function ($record) {
                    $disfraz = Disfraz::where('id', $record->disfraz_id)->first();
                    $otrasPiezas = DisfrazPieza::where('disfraz_id', $record->disfraz_id)
                        ->where('pieza_id', $record->pieza_id) // Diferente estado
                        ->get();
                    foreach ($otrasPiezas as $pieza) {
                        $pieza->delete();
                    }
                    $precioPieza = round($record->price * 0.2, 2);
                    $disfrazprecio = round($disfraz->price - $precioPieza, 2);
                    $disfraz->update([
                        'price' => $disfrazprecio,
                    ]);
                    $existeOtraReferencia = DisfrazPieza::where('pieza_id', $record->pieza_id)
                        ->where('disfraz_id', '!=', $record->disfraz_id) // ✅ Excluir el disfraz actual
                        ->exists();
                    if (!$existeOtraReferencia) {
                        Pieza::where('id', $record->pieza_id)->delete();
                    }
                }),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }
}
