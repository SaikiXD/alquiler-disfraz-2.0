<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DevolucionResource\Pages;
use App\Filament\Resources\DevolucionResource\RelationManagers;
use App\Models\Alquiler;
use App\Models\AlquilerDisfraz;
use App\Models\AlquilerDisfrazPieza;
use App\Models\Devolucion;
use App\Models\Disfraz;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DevolucionResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static ?string $model = Devolucion::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-check';
    public static function getModelLabel(): string
    {
        return 'Devolución';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Devoluciones';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Información de la Devolución')
                ->schema([
                    Forms\Components\Hidden::make('alquiler_id')
                        ->label('Alquiler ID')
                        ->default(function ($get) {
                            // Obtén el alquiler_id desde la consulta de la URL
                            return request()->query('alquiler_id');
                        }) // Ocultar el campo, pero enviarlo en el formulario
                        ->required(), // Deshabilitar para que no se pueda editar
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('cliente_name')
                            ->label('Nombre del Cliente')
                            ->default(function ($get) {
                                // Obtén el alquiler_id desde la consulta de la URL
                                $alquilerId = request()->query('alquiler_id');

                                return Alquiler::find($alquilerId)?->cliente->name ?? 'No disponible';
                            })
                            ->disabled(), // Deshabilitar para que no se pueda editar
                        Forms\Components\DatePicker::make('fecha_devolucion')
                            ->label('Fecha de Devolución')
                            ->default(function ($get) {
                                // Obtén el alquiler_id desde la consulta de la URL
                                $alquilerId = request()->query('alquiler_id');

                                return Alquiler::find($alquilerId)?->fecha_devolucion ?? 'No disponible';
                            })
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('fecha_devolucion_real')
                            ->label('Fecha de Devolución')
                            ->default(now())
                            ->displayFormat('d/m/Y H:i')
                            ->required(),
                        Forms\Components\TextInput::make('multa')
                            ->prefix('Bs')
                            ->default(function (callable $get) {
                                $fechaDevolucion = Carbon::parse($get('fecha_devolucion')) ?: now();

                                $fechaReal = Carbon::parse($get('fecha_devolucion_real')) ?: now();
                                // Calcular la diferencia de días
                                $diasDeRetraso = floor($fechaDevolucion->diffInDays($fechaReal, false));
                                $alquilerId = request()->query('alquiler_id');
                                $alquiler = AlquilerDisfraz::where('alquiler_id', $alquilerId)->first();
                                if ($diasDeRetraso > 0) {
                                    $multadisfraz = $alquiler->precio_unitario * $alquiler->cantidad;
                                    $multa = $multadisfraz * $diasDeRetraso;
                                    return $multa;
                                } else {
                                    return 0;
                                }
                            })
                            ->required(),
                    ]),
                ])
                ->columnSpanFull(),
            Forms\Components\Section::make('Piezas Dañadas')->schema([
                Repeater::make('devolucionPiezas') //este modelo lo cree para usar repeater  con una tabla pivote
                    ->relationship()
                    ->label(false)
                    ->defaultItems(0)
                    ->reorderable(false)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('alquiler_disfraz_pieza_id')
                            ->label('Piezas Alquiladas')
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->options(function (callable $get) {
                                // Necesitas saber el alquiler_id para filtrar
                                $alquilerId = $get('../../alquiler_id');

                                // Filtrar solo las piezas que pertenecen a ese alquiler
                                return AlquilerDisfrazPieza::whereHas('alquilerDisfraz', function ($query) use (
                                    $alquilerId
                                ) {
                                    $query->where('alquiler_id', $alquilerId);
                                })
                                    ->get()
                                    ->mapWithKeys(function ($item) {
                                        $nombreDisfraz = $item->alquilerDisfraz->disfraz->name ?? 'Disfraz';
                                        $nombrePieza = $item->pieza->name ?? 'Pieza';
                                        return [
                                            $item->id => "$nombreDisfraz - $nombrePieza (Alquiladas: {$item->cantidad_reservada})",
                                        ];
                                    });
                            })
                            ->searchable()
                            ->reactive()
                            ->required(),

                        TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(function (callable $get) {
                                // Recuperar la cantidad reservada para no exceder la devuelta
                                $alquilerDisfrazPieza = AlquilerDisfrazPieza::find($get('alquiler_disfraz_pieza_id'));
                                return $alquilerDisfrazPieza?->cantidad_reservada ?? 1;
                            })
                            ->required(),
                        Forms\Components\Radio::make('estado_pieza')
                            ->label('Estado de la pieza')
                            ->options([
                                'dañado' => 'Dañado',
                                'perdido' => 'Perdido',
                            ])
                            ->inline()
                            ->required(),
                    ])
                    ->columns(2)
                    ->addActionLabel('Añadir Pieza'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alquiler_id')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('fecha_devolucion_real')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('multa')->numeric()->sortable(),

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
