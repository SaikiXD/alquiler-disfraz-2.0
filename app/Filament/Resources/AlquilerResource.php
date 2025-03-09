<?php

namespace App\Filament\Resources;

use App\Enums\AlquilerStatusEnum;
use App\Filament\Resources\AlquilerResource\Pages;
use App\Filament\Resources\AlquilerResource\RelationManagers;
use App\Models\Alquiler;
use App\Models\AlquilerDisfrazPieza;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\DB;
use App\Services\AlquilerService;

class AlquilerResource extends Resource
{
    protected static ?string $model = Alquiler::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informacion del cliente')->schema([
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'name')
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('ci')->required()->numeric(),
                        Forms\Components\TextInput::make('email')->email()->maxLength(255),
                        Forms\Components\TextInput::make('address')->required()->maxLength(255),
                        Forms\Components\TextInput::make('phone')->tel()->required()->numeric(),
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn() => \Illuminate\Support\Facades\Auth::user()?->id)
                            ->required(),
                    ])
                    ->required(),
            ]),
            Section::make('Informacion de Disfraz')->schema([
                Repeater::make('alquilerDisfrazs') //este modelo lo cree para usar repeater  con una tabla pivote
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('disfraz_id')
                            ->label('Disfraz')
                            ->relationship('disfraz', 'name')
                            ->searchable()
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('precio_unitario', Disfraz::obtenerPrecio($state));
                                } else {
                                    $set('precio_unitario', 0);
                                }
                            }),

                        Forms\Components\CheckboxList::make('piezas_seleccionadas')
                            ->label('Selecciona las piezas a usar')
                            ->options(
                                fn($get) => DisfrazPieza::where('disfraz_id', $get('disfraz_id'))
                                    ->join('piezas', 'disfraz_pieza.pieza_id', '=', 'piezas.id') // Unir con la tabla de piezas
                                    ->get(['piezas.id', 'piezas.name', 'disfraz_pieza.stock', 'disfraz_pieza.status']) // Obtener nombre, stock y estado
                                    ->groupBy('id') // Agrupar por pieza
                                    ->mapWithKeys(function ($piezas) {
                                        $pieza = $piezas->first(); // Obtener la primera entrada (ya que están agrupadas)
                                        $stockDisponible = $piezas->where('status', 'disponible')->sum('stock');
                                        $stockReservado = $piezas->where('status', 'reservado')->sum('stock');
                                        return [
                                            $pieza->id => "{$pieza->name} (Disponible: {$stockDisponible}, Reservado: {$stockReservado})",
                                        ];
                                    })
                                    ->toArray()
                            )
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!is_array($state)) {
                                    $state = json_decode($state, true) ?? []; // Convertir JSON string a array si es necesario
                                }
                                if ($state) {
                                    $stockMinimo = PHP_INT_MAX;
                                    foreach ($state as $piezaId) {
                                        $stockDisponible = DisfrazPieza::where('pieza_id', $piezaId)
                                            ->where('status', 'disponible')
                                            ->sum('stock');
                                        // Si el stock disponible es menor que la cantidad solicitada, se ajusta
                                        $stockMaximo = max($stockMinimo, $stockDisponible);
                                    }
                                    // Actualizar el máximo permitido en 'cantidad'
                                    $set('stock_disponible', $stockMaximo);

                                    // Guardar como JSON la cantidad que realmente se puede alquilar
                                }
                            })
                            ->afterStateHydrated(function ($state, callable $set, $get) {
                                if (!is_array($state)) {
                                    $state = json_decode($state, true) ?? [];
                                }
                                if ($state) {
                                    $stockMinimo = 0;
                                    $alquilerId = $get('alquiler_id');
                                    foreach ($state as $piezaId) {
                                        $stockDisponible = DisfrazPieza::where('pieza_id', $piezaId)
                                            ->where('status', 'disponible')
                                            ->sum('stock');
                                        $stockReservado = AlquilerDisfrazPieza::where('pieza_id', $piezaId)
                                            ->where('alquiler_disfraz_id', $alquilerId)
                                            ->sum('cantidad_reservada');
                                        $stocktotal = $stockDisponible + $stockReservado;
                                        $stockMinimo = max($stockMinimo, $stocktotal);
                                    }
                                    $set('stock_disponible', $stockMinimo);
                                }
                            }),

                        Forms\Components\TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(fn($get) => $get('stock_disponible')) // Evita alquilar más de lo disponible
                            ->required()
                            ->default(fn($get) => AlquilerService::obtenerStockMinimoDisfraz($get('disfraz_id'))), // Carga el stock en edición
                        Forms\Components\TextInput::make('precio_unitario')
                            ->label('Precio Unitario')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ]),
            ]),
            Section::make('Garantia')
                ->columns(3)
                ->schema([
                    Forms\Components\Select::make('tipo_garantia')
                        ->label('Tipo de Garantía')
                        ->options([
                            'dinero' => 'Dinero',
                            'documento' => 'Documento',
                            'objeto' => 'Objeto',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('valor_garantia')
                        ->label('Valor de Garantía')
                        ->numeric()
                        ->required(),
                    Forms\Components\DatePicker::make('fecha_alquiler')
                        ->label('Fecha de Alquiler')
                        ->native(false)
                        ->required(),
                    Forms\Components\DatePicker::make('fecha_devolucion')
                        ->label('Fecha de Devolución')
                        ->native(false)
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->options([
                            AlquilerStatusEnum::PENDIENTE->value => AlquilerStatusEnum::PENDIENTE->getLabel(),
                            AlquilerStatusEnum::ALQUILADO->value => AlquilerStatusEnum::ALQUILADO->getLabel(),
                        ])
                        ->default(AlquilerStatusEnum::PENDIENTE->value)
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.name'),
                Tables\Columns\TextColumn::make('fecha_alquiler'),
                Tables\Columns\TextColumn::make('fecha_devolucion'),
                Tables\Columns\TextColumn::make('status')->badge()->searchable(),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('success'),
                Tables\Actions\EditAction::make()->visible(
                    fn($record) => $record->status->value === AlquilerStatusEnum::PENDIENTE->value
                ),
            ])
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
            'index' => Pages\ListAlquilers::route('/'),
            'create' => Pages\CreateAlquiler::route('/create'),
            'edit' => Pages\EditAlquiler::route('/{record}/edit'),
            'view' => Pages\ViewAlquiler::route('/{record}'),
        ];
    }
}
