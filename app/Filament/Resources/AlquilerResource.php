<?php

namespace App\Filament\Resources;

use App\Enums\AlquilerStatusEnum;
use App\Enums\DisfrazStatusEnum;
use App\Filament\Resources\AlquilerResource\Pages;
use App\Filament\Resources\AlquilerResource\RelationManagers;
use App\Models\Alquiler;
use App\Models\AlquilerDisfraz;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Split;

class AlquilerResource extends Resource
{
    protected static ?string $model = Alquiler::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function getPluralModelLabel(): string
    {
        return 'Alquileres';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('alquiler_id')->default(fn() => request()->route('record')?->id),
            Section::make('Informacion del cliente')
                ->icon('heroicon-o-user')
                ->schema([
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
            Section::make('Informacion de Disfraz')
                ->icon('heroicon-o-tag')
                ->schema([
                    Repeater::make('alquilerDisfrazs') //este modelo lo cree para usar repeater  con una tabla pivote
                        ->relationship()
                        ->label(false)
                        ->addActionLabel('Añadir disfraz al pedido')
                        ->collapsed()
                        ->defaultItems(0)
                        ->minItems(1)
                        ->schema([
                            Hidden::make('id'), // Esto es importante para que $get('../../id') funcione
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\Radio::make('modo_alquiler')
                                    ->label('Modo de alquiler del Disfraz')
                                    ->options([
                                        'completo' => 'Todo el conjunto',
                                        'por_pieza' => 'Elegir piezas',
                                    ])
                                    ->default('completo')
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->reactive()
                                    ->required()
                                    ->disabled(fn($get) => filled($get('id')))
                                    ->afterStateUpdated(function (callable $set) {
                                        $set('piezas_completas', []);
                                        $set('piezas_seleccionadas', []);
                                        $set('precio_unitario', 0);
                                    }),
                                Forms\Components\Select::make('disfraz_id')
                                    ->label('Disfraz')
                                    ->relationship('disfraz', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->disabled(fn($get) => filled($get('id')))
                                    ->reactive()
                                    //->disabled(fn() => request()->route('record') !== null)
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        if (request()->routeIs('filament.disfraces.resources.alquilers.view')) {
                                            return $record->name; // Solo el nombre si estás en la vista
                                        }
                                        $alquilerId = request()->route('record');
                                        $stockDisponible = $record->stock_disponible;
                                        if ($record->status === DisfrazStatusEnum::INCOMPLETO) {
                                            $stockDisponible = 0;
                                        }
                                        $reservado = AlquilerDisfraz::where('disfraz_id', $record->id)
                                            ->where('alquiler_id', $alquilerId)
                                            ->value('cantidad');
                                        $stockDisponible += $reservado;

                                        return "{$record->name} (Stock Disponible: {$stockDisponible})";
                                    })
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if (!$state) {
                                            $set('precio_unitario', 0);
                                            $set('piezas_completas', []);
                                            return;
                                        }
                                        $set('precio_unitario', Disfraz::obtenerPrecio($state));
                                        if ($get('modo_alquiler') === 'completo') {
                                            $piezas = DisfrazPieza::where('disfraz_id', $state)
                                                ->with('pieza')
                                                ->where('status', 'disponible')
                                                ->get();

                                            $set(
                                                'piezas_completas',
                                                $piezas->pluck('pieza.name')->filter()->values()->toArray()
                                            );
                                        }
                                    })
                                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                        if ($get('modo_alquiler') === 'completo') {
                                            $piezas = DisfrazPieza::where('disfraz_id', $state)
                                                ->with('pieza')
                                                ->where('status', 'disponible')
                                                ->get();
                                            $set(
                                                'piezas_completas',
                                                $piezas->pluck('pieza.name')->filter()->values()->toArray()
                                            );
                                        }
                                    }),
                                Forms\Components\TagsInput::make('piezas_completas')
                                    ->label(false)
                                    ->placeholder('Piezas Incluidas')
                                    ->visible(fn($get) => $get('modo_alquiler') === 'completo')
                                    ->disabled()
                                    ->columnSpanFull(),
                                Forms\Components\CheckboxList::make('piezas_seleccionadas')
                                    ->label('Selecciona las piezas a usar')
                                    ->columns(2) // o 3 si tenés muchas piezas
                                    ->disabled(fn($get) => filled($get('id')))
                                    ->visible(fn($get) => $get('modo_alquiler') === 'por_pieza')
                                    ->options(
                                        fn($get) => $get('disfraz_id')
                                            ? DisfrazPieza::with('pieza')
                                                ->where('disfraz_id', $get('disfraz_id'))
                                                ->whereIn('status', ['disponible', 'reservado'])
                                                ->get()
                                                ->groupBy('pieza_id')
                                                ->mapWithKeys(function ($items, $piezaId) {
                                                    $pieza = $items->first()->pieza;
                                                    $filaDisponible = $items->firstWhere('status', 'disponible');
                                                    $filaReservado = $items->firstWhere('status', 'reservado');
                                                    $stockDisponible = $filaDisponible?->stock ?? 0;
                                                    $stockReservado = $filaReservado?->stock ?? 0;
                                                    return [
                                                        $piezaId => "{$pieza->name} (Disponible: {$stockDisponible}, Reservado: {$stockReservado})",
                                                    ];
                                                })
                                                ->toArray()
                                            : []
                                    )
                                    ->columnSpanFull()
                                    ->reactive(),
                            ]),
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('cantidad')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->reactive()
                                    ->minValue(1)
                                    ->default(0)
                                    ->disabled(fn($get) => filled($get('id')))
                                    ->maxValue(
                                        fn($get) => match ($get('modo_alquiler')) {
                                            'por_pieza' => DisfrazPieza::whereIn(
                                                'pieza_id',
                                                $get('piezas_seleccionadas') ?? []
                                            )
                                                ->whereIn('status', ['disponible', 'reservado'])
                                                ->get()
                                                ->groupBy('pieza_id')
                                                ->map(fn($piezas) => $piezas->sum('stock'))
                                                ->max() ?? 0,

                                            'completo' => DisfrazPieza::where('disfraz_id', $get('disfraz_id'))
                                                ->whereIn('status', ['disponible', 'reservado'])
                                                ->get()
                                                ->groupBy('pieza_id')
                                                ->map(fn($items) => $items->sum('stock'))
                                                ->min() ?? 0,
                                        }
                                    )

                                    ->lazy()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $set('total', self::calcularTotal($get));
                                    })
                                    ->required(),
                                Forms\Components\TextInput::make('precio_unitario')
                                    ->label('Precio Unitario')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled(fn($get) => filled($get('id')))
                                    ->minValue(1)
                                    ->reactive()
                                    ->lazy()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        $set('total', self::calcularTotal($get));
                                    })
                                    ->required(),
                            ]),
                        ])
                        ->afterStateUpdated(function (callable $get, callable $set, $state) {
                            if (is_array($state)) {
                                $set('total', self::calcularTotal($get));
                            }
                        })
                        ->itemLabel(fn(array $state) => Disfraz::find($state['disfraz_id'])?->name ?? 'Nuevo disfraz'),
                ]),
            Split::make([
                Section::make('Informacion de la Garantia')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        Grid::make(2)->schema([
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
                        ]),
                        Forms\Components\FileUpload::make('image_path_garantia')
                            ->label('Imagen de Referencia')
                            ->image()
                            ->imageEditor()
                            ->maxSize(1024),
                        Forms\Components\Textarea::make('description')->label('Descripcion'),
                    ])
                    ->grow(2),
                Section::make('Detalles del Alquiler')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('fecha_alquiler')
                                ->label('Fecha de Alquiler')
                                ->default(now())

                                ->required(),
                            Forms\Components\DatePicker::make('fecha_devolucion')
                                ->label('Fecha de Devolución')
                                ->afterOrEqual('fecha_alquiler')
                                ->required(),
                        ]),
                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->prefix('Bs')
                            ->disabled()
                            ->numeric()
                            ->afterStateHydrated(function (callable $get, callable $set) {
                                $set('total', self::calcularTotal($get));
                            })
                            ->dehydrated(false)
                            ->default(0),
                        Radio::make('status')
                            ->label('Estado')
                            ->options([
                                AlquilerStatusEnum::PENDIENTE->value => 'Pendiente',
                                AlquilerStatusEnum::ALQUILADO->value => 'Alquilado',
                            ])
                            ->default(AlquilerStatusEnum::PENDIENTE->value)
                            ->inline()
                            ->inlineLabel(false)
                            ->required(),
                    ])
                    ->grow(1),
            ])
                ->columnSpan('full')
                ->from('md'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.name'),
                Tables\Columns\TextColumn::make('fecha_alquiler')->date(),
                Tables\Columns\TextColumn::make('fecha_devolucion')->date(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(
                        fn($state) => $state instanceof AlquilerStatusEnum ? $state->name : (string) $state
                    ),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('success'),
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $record->status->value === AlquilerStatusEnum::PENDIENTE->value)
                    ->slideOver(),
                Tables\Actions\Action::make('marcarComoAlquilado')
                    ->label('Alquilar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar cambio de estado')
                    ->visible(fn($record) => $record->status->value === AlquilerStatusEnum::PENDIENTE->value)
                    ->action(function ($record) {
                        $record->update([
                            'status' => AlquilerStatusEnum::ALQUILADO->value,
                        ]);
                        Notification::make()
                            ->title('Estado actualizado')
                            ->body('El estado se cambió a ALQUILADO correctamente.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('devolucion')
                    ->label('Ir a Devolución')
                    ->color('primary') // O el color que prefieras
                    ->icon('heroicon-o-arrow-right') // Icono opcional
                    ->url(
                        fn($record) => route('filament.disfraces.resources.devolucions.create', [
                            'alquiler_id' => $record->id, // Pasar el ID del alquiler a la ruta
                        ])
                    ) // Ruta a la página de edición de Devolución
                    ->visible(fn($record) => $record->status->value === AlquilerStatusEnum::ALQUILADO->value),
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
    protected static function calcularTotal(callable $get): float
    {
        $disfraces = $get('alquilerDisfrazs') ?? [];

        $total = 0;
        foreach ($disfraces as $item) {
            $cantidad = is_numeric($item['cantidad'] ?? null) ? (float) $item['cantidad'] : 0;
            $precio = is_numeric($item['precio_unitario'] ?? null) ? (float) $item['precio_unitario'] : 0;
            $total += $cantidad * $precio;
        }

        return $total;
    }
}
