<?php

namespace App\Filament\Resources;

use App\Enums\AlquilerStatusEnum;
use App\Enums\DisfrazPiezaStatusEnum;
use App\Filament\Resources\AlquilerResource\Pages;
use App\Models\Alquiler;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;

class AlquilerResource extends Resource
{
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 1;
    protected static ?string $model = Alquiler::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function getPluralModelLabel(): string
    {
        return 'Alquileres';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informacion de Alquiler')
                    ->schema([
                        Forms\Components\Select::make('cliente_id')
                            ->label('Registrar o Seleccionar Cliente')
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

                        Repeater::make('alquilerDisfrazs') //este modelo lo cree para usar repeater  con una tabla pivote
                            ->relationship()
                            ->label('Disfraz/s Alquilado/s')
                            ->addActionLabel('Añadir disfraz al pedido')
                            ->collapsed()
                            ->defaultItems(0)
                            ->minItems(1)
                            ->schema([
                                Forms\Components\Select::make('disfraz_id')
                                    ->label('Disfraz')
                                    ->relationship('disfraz', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        return "{$record->nombre} - {$record->genero}";
                                    })
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!$state) {
                                            $set('precio_alquiler', 0);
                                            $set('piezas_completas', []);
                                            return;
                                        }
                                        $set('precio_alquiler', Disfraz::obtenerPrecio($state));
                                        $set('cantidad', 0);
                                        $set('total_disfraz', 0);
                                    })
                                    ->columnSpan(3),
                                Forms\Components\CheckboxList::make('piezas_seleccionadas')
                                    ->label(false)
                                    ->columns(2) // o 3 si tenés muchas piezas
                                    ->bulkToggleable()
                                    ->options(
                                        fn($get) => $get('disfraz_id')
                                            ? DisfrazPieza::with('pieza')
                                                ->where('disfraz_id', $get('disfraz_id'))
                                                ->where('status', DisfrazPiezaStatusEnum::DISPONIBLE)
                                                ->get()
                                                ->groupBy('pieza_id')
                                                ->mapWithKeys(function ($items, $piezaId) {
                                                    $pieza = $items->first()->pieza;
                                                    $filaDisponible = $items->firstWhere('status', 'disponible');
                                                    $stockDisponible = $filaDisponible?->stock ?? 0;
                                                    return [
                                                        $piezaId => "{$pieza->name} - stock: {$stockDisponible}",
                                                    ];
                                                })
                                                ->toArray()
                                            : []
                                    )
                                    ->columnSpanFull()
                                    ->reactive(),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('cantidad')
                                        ->label('Cantidad')
                                        ->numeric()
                                        ->reactive()
                                        ->minValue(1)
                                        ->default(0)
                                        ->maxValue(
                                            fn($get) => DisfrazPieza::whereIn(
                                                'pieza_id',
                                                $get('piezas_seleccionadas') ?? []
                                            )
                                                ->where('status', DisfrazPiezaStatusEnum::DISPONIBLE)
                                                ->get()
                                                ->pluck('stock')
                                                ->min() ?? 0
                                        )
                                        ->lazy()
                                        ->afterStateUpdated(function (callable $get, callable $set) {
                                            $set('total', self::calcularTotal($get));
                                            $set('sub_total', self::calcularSubTotal($get));
                                            $set('total_disfraz', self::totalDisfraz($get));
                                        })
                                        ->required(),
                                    Forms\Components\TextInput::make('precio_alquiler')
                                        ->label('Precio alquiler')
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(1)
                                        ->reactive()
                                        ->lazy()
                                        ->afterStateUpdated(function (callable $get, callable $set) {
                                            $set('total', self::calcularTotal($get));
                                            $set('sub_total', self::calcularSubTotal($get));
                                            $set('total_disfraz', self::totalDisfraz($get));
                                        })
                                        ->required(),
                                    Forms\Components\TextInput::make('total_disfraz')
                                        ->label('Total Disfraz')
                                        ->prefix('Bs')
                                        ->disabled()
                                        ->numeric()
                                        ->afterStateHydrated(function (callable $get, callable $set) {
                                            $set('total_disfraz', self::totalDisfraz($get));
                                        })
                                        ->dehydrated(false)
                                        ->default(0),
                                ]),
                            ])
                            ->afterStateUpdated(function (callable $get, callable $set, $state) {
                                if (is_array($state)) {
                                    $set('total', self::calcularTotal($get));
                                    $set('sub_total', self::calcularSubTotal($get));
                                }
                            })
                            ->itemLabel(function (array $state): string {
                                $disfraz = Disfraz::find($state['disfraz_id'] ?? null);
                                if (!$disfraz) {
                                    return 'Nuevo disfraz';
                                }
                                if (request()->routeIs('filament.disfraces.resources.alquilers.view')) {
                                    return $disfraz->nombre; // Solo el nombre si estás en la vista
                                }
                                return "{$disfraz->nombre} - Stock del Traje Completo: {$disfraz->stock_disponible}";
                            }),
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
                    ])
                    ->columnSpan(2),
                Section::make('Informacion de la Garantia')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('tipo_garantia')
                                ->label('Tipo de Garantía')
                                ->placeholder('selecciona...')
                                ->options([
                                    'dinero' => 'Dinero',
                                    'documento' => 'Documento',
                                    'objeto' => 'Objeto',
                                ])
                                ->reactive()
                                ->default('dinero')
                                ->required(),
                            Forms\Components\TextInput::make('valor_garantia')
                                ->label('Valor de Garantía')
                                ->numeric()
                                ->reactive()
                                ->lazy()
                                ->required()
                                ->afterStateUpdated(function (callable $get, callable $set) {
                                    $set('total', self::calcularTotal($get));
                                }),
                        ]),
                        Forms\Components\FileUpload::make('image_path_garantia')
                            ->label('Imagen del Objeto')
                            ->image()
                            ->imageEditor()
                            ->visible(fn($get) => in_array($get('tipo_garantia'), ['objeto', 'documento']))
                            ->maxSize(1024),
                        Forms\Components\Textarea::make('detalles_garantia')->label('Detalles de la Garantia'),
                        Forms\Components\TextInput::make('sub_total')
                            ->label('SubTotal')
                            ->prefix('Bs')
                            ->disabled()
                            ->numeric()
                            ->dehydrated(false)
                            ->default(0)
                            ->afterStateHydrated(function (callable $get, callable $set) {
                                $set('sub_total', self::calcularSubTotal($get));
                            }),
                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->prefix('Bs')
                            ->disabled()
                            ->numeric()
                            ->dehydrated(false)
                            ->default(0)
                            ->afterStateHydrated(function (callable $get, callable $set) {
                                $set('total', self::calcularTotal($get));
                            }),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.name')->searchable()->sortable(),
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
                Tables\Actions\Action::make('devolucion')
                    ->label('Devolución')
                    ->color('primary') // O el color que prefieras
                    ->icon('heroicon-o-arrow-right') // Icono opcional
                    ->url(
                        fn($record) => route('filament.disfraces.resources.devolucions.create', [
                            'alquiler_id' => $record->id, // Pasar el ID del alquiler a la ruta
                        ])
                    ) // Ruta a la página de edición de Devolución
                    ->visible(fn($record) => $record->status->value === AlquilerStatusEnum::ALQUILADO->value),
                Tables\Actions\Action::make('pdf')
                    ->label('Recibo')
                    ->color('secondary')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Model $record) {
                        return response()->streamDownload(
                            function () use ($record) {
                                echo Pdf::loadHtml(
                                    Blade::render('pdf.recibo-alquiler', ['record' => $record])
                                )->output();
                            },
                            $record->number . '.pdf',

                            [
                                'Content-Type' => 'application/pdf',
                            ]
                        );
                    }),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlquilers::route('/'),
            'create' => Pages\CreateAlquiler::route('/create'),
            'view' => Pages\ViewAlquiler::route('/{record}'),
        ];
    }
    protected static function calcularTotal(callable $get): float
    {
        $disfraces = $get('alquilerDisfrazs') ?? [];

        $garantia = (float) $get('valor_garantia');
        $total = 0;
        foreach ($disfraces as $item) {
            $cantidad = is_numeric($item['cantidad'] ?? null) ? (float) $item['cantidad'] : 0;
            $precio = is_numeric($item['precio_alquiler'] ?? null) ? (float) $item['precio_alquiler'] : 0;
            $total += $cantidad * $precio;
        }
        $total += $garantia;
        return $total;
    }
    protected static function calcularSubTotal(callable $get): float
    {
        $disfraces = $get('alquilerDisfrazs') ?? [];
        $total = 0;
        foreach ($disfraces as $item) {
            $cantidad = is_numeric($item['cantidad'] ?? null) ? (float) $item['cantidad'] : 0;
            $precio = is_numeric($item['precio_alquiler'] ?? null) ? (float) $item['precio_alquiler'] : 0;
            $total += $cantidad * $precio;
        }
        return $total;
    }
    protected static function totalDisfraz(callable $get): float
    {
        $precioDisfraz = (float) $get('precio_alquiler') ?? 0;
        $cantidadDisfraz = (float) $get('cantidad') ?? 0;
        $total = $cantidadDisfraz * $precioDisfraz;
        return $total;
    }
}
