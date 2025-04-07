<?php

namespace App\Filament\Resources\DisfrazResource\RelationManagers;

use App\Enums\DisfrazPiezaStatusEnum;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use App\Models\Pieza;
use App\Models\Talla;
use App\Models\Tipo;
use App\Models\TipoPieza;
use Filament\Actions\ReplicateAction;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class PiezasRelationManager extends RelationManager
{
    protected static string $relationship = 'disfrazPiezas';
    public static function getModelLabel(): string
    {
        return 'pieza del traje';
    }

    public static function getPluralModelLabel(): string
    {
        return 'piezas del traje';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre de la Pieza')
                    ->maxLength(255)
                    ->required()
                    ->afterStateHydrated(
                        fn(Forms\Components\TextInput $component, $state, $record) => $component->state(
                            $state ??= $record?->pieza?->name
                        )
                    ),
                Forms\Components\Select::make('tipo_pieza')
                    ->label('Tipo')
                    ->options(TipoPieza::pluck('name', 'id'))
                    ->live()
                    ->required()
                    ->afterStateHydrated(
                        fn(Forms\Components\Select $component, $state, $record) => $component->state(
                            $state ??= $record?->pieza?->tipo_pieza_id
                        )
                    ),
                Fieldset::make('Características de la Pieza')
                    ->schema([
                        Forms\Components\Hidden::make('pieza_id'),
                        ColorPicker::make('color')
                            ->label('Color')
                            ->required()
                            ->default('#FFFFFF')
                            ->afterStateHydrated(
                                fn(ColorPicker $component, $state, $record) => $component->state(
                                    $state ??= $record?->pieza?->color
                                )
                            ),
                        Forms\Components\Select::make('talla')
                            ->label('Talla')
                            ->reactive()
                            ->options(function (Get $get): Collection {
                                return Talla::whereHas('tipos', function ($q) use ($get) {
                                    $q->where('tipo_pieza_id', $get('tipo_pieza'));
                                })->pluck('name', 'id');
                            })
                            ->live()
                            ->placeholder('Seleccionar Talla')
                            ->required()
                            ->afterStateHydrated(
                                fn(Forms\Components\Select $component, $state, $record) => $component->state(
                                    $state ??= $record?->pieza?->talla_id
                                )
                            ),

                        Forms\Components\TextInput::make('material')
                            ->required()
                            ->afterStateHydrated(
                                fn(Forms\Components\TextInput $component, $state, $record) => $component->state(
                                    $state ??= $record?->pieza?->material
                                )
                            ),
                    ])
                    ->columns(3),
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('stock')->label('Cantidad')->numeric()->minValue(0)->required(),
                    Forms\Components\TextInput::make('valor_reposicion')
                        ->label('Costo de Reposicion')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->prefix('Bs')
                        ->step(0.01)
                        ->afterStateHydrated(
                            fn(Forms\Components\TextInput $component, $state, $record) => $component->state(
                                $state ??= $record?->pieza?->valor_reposicion
                            )
                        ),
                ]),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('pieza.name')->label('Pieza')->sortable(),
                Tables\Columns\TextColumn::make('stock')->sortable(),
                ColorColumn::make('pieza.color') // Muestra el cuadro de color
                    ->label('Color')
                    ->copyable(),

                Tables\Columns\TextColumn::make('pieza.talla.name')->label('Talla'),
                Tables\Columns\TextColumn::make('pieza.material'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado') // Agregar la columna de estado
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        DisfrazPiezaStatusEnum::DISPONIBLE->value => 'Disponible',
                        DisfrazPiezaStatusEnum::ALQUILADO->value => 'Alquilado',
                        DisfrazPiezaStatusEnum::RETIRADO->value => 'Retirado',
                    ]) // Obtiene las opciones del Enum
                    ->attribute('status')
                    ->default([DisfrazPiezaStatusEnum::DISPONIBLE->value]),
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
                        $disfrazId = $this->getOwnerRecord()->id; // Obtener el registro padre (el disfraz actual del RelationManager)
                        $piezaId = $data['pieza_id'];
                        foreach (DisfrazPiezaStatusEnum::cases() as $status) {
                            $piezaOriginal = DisfrazPieza::where('status', $status->value)
                                ->where('pieza_id', $piezaId)
                                ->first();
                            $newPieza = $piezaOriginal->replicate(); // Crear una réplica de la pieza seleccionada
                            $newPieza->disfraz_id = $disfrazId;
                            $newPieza->save();
                        }
                    })
                    ->successNotificationTitle('Pieza asociada exitosamente'),

                Tables\Actions\CreateAction::make()
                    ->label('Agregar Nueva Pieza')
                    ->icon('heroicon-o-plus')
                    ->form(fn(Form $form) => $this->form($form))
                    ->modalSubmitActionLabel('Guardar Pieza')
                    ->createAnother(false)
                    ->mutateFormDataUsing(function (array $data) {
                        $pieza = Pieza::create([
                            'tipo_pieza_id' => $data['tipo_pieza'],
                            'talla_id' => $data['talla'],
                            'name' => $data['name'],
                            'valor_reposicion' => $data['valor_reposicion'],
                            'color' => $data['color'],
                            'material' => $data['material'],
                        ]);

                        $data['pieza_id'] = $pieza->id;

                        unset(
                            $data['tipo_pieza'],
                            $data['talla'],
                            $data['name'],
                            $data['valor_reposicion'],
                            $data['color'],
                            $data['material']
                        ); //aqui elimino los datos del array $data

                        return $data;
                    })
                    ->after(function ($record) {
                        // Crear los tres registros adicionales con estados diferentes
                        $estadosAdicionales = [
                            DisfrazPiezaStatusEnum::ALQUILADO->value,
                            DisfrazPiezaStatusEnum::RETIRADO->value,
                        ];

                        foreach ($estadosAdicionales as $estado) {
                            DisfrazPieza::create([
                                'disfraz_id' => $record->disfraz_id,
                                'pieza_id' => $record->pieza_id,
                                'stock' => 0,
                                'status' => $estado,
                            ]);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function (array $data) {
                    // Buscar la pieza relacionada
                    $pieza = Pieza::find($data['pieza_id']);

                    if ($pieza) {
                        $pieza->update([
                            'tipo_pieza_id' => $data['tipo_pieza'],
                            'talla_id' => $data['talla'],
                            'name' => $data['name'],
                            'valor_reposicion' => $data['valor_reposicion'],
                            'color' => $data['color'],
                            'material' => $data['material'],
                        ]);
                    }
                    unset(
                        $data['tipo_pieza'],
                        $data['talla'],
                        $data['name'],
                        $data['valor_reposicion'],
                        $data['color'],
                        $data['material']
                    );

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
