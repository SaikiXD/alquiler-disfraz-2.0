<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlquilerResource\Pages;
use App\Filament\Resources\AlquilerResource\RelationManagers;
use App\Models\Alquiler;
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
                                    $set('piezas_disponibles', DisfrazPieza::obtenerPiezasPorDisfraz($state));
                                } else {
                                    $set('precio_unitario', 0);
                                    $set('piezas_disponibles', []);
                                }
                            }),
                        //Forms\Components\TextInput::make('stock_disponible')->label('Stock Disponible')->disabled(),
                        Forms\Components\CheckboxList::make('piezas_seleccionadas')
                            ->label('Selecciona las piezas a usar')
                            ->options(
                                fn($get) => DisfrazPieza::where('disfraz_id', $get('disfraz_id'))
                                    ->join('piezas', 'disfraz_pieza.pieza_id', '=', 'piezas.id') // Unir con la tabla de piezas
                                    ->get(['piezas.id', 'piezas.name', 'disfraz_pieza.stock']) // Obtener nombre y stock
                                    ->mapWithKeys(
                                        fn($pieza) => [$pieza->id => "{$pieza->name} (Stock: {$pieza->stock})"]
                                    ) // Formato "Nombre (Stock: X)"
                                    ->toArray()
                            )
                            ->reactive(),

                        Forms\Components\TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(fn($get) => $get('stock_disponible') ?? 0) // Evita alquilar más de lo disponible
                            ->required()
                            ->default(fn($get) => AlquilerService::obtenerStockMinimoDisfraz($get('disfraz_id'))) // Carga el stock en edición
                            ->afterStateUpdated(function ($state, callable $get) {
                                $disfrazId = $get('disfraz_id');
                                if ($disfrazId && $state > 0) {
                                    AlquilerService::reservarPiezas($disfrazId, $state);
                                }
                            }),
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
                    Forms\Components\ToggleButtons::make('status')
                        ->options([
                            'pendiente' => 'Pendiente',
                            'en_progreso' => 'Alquilar',
                        ])
                        ->inline()
                        ->default('pendiente')
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
            'index' => Pages\ListAlquilers::route('/'),
            'create' => Pages\CreateAlquiler::route('/create'),
            'edit' => Pages\EditAlquiler::route('/{record}/edit'),
        ];
    }
}
