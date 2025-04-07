<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DevolucionResource\Pages;
use App\Filament\Resources\DevolucionResource\RelationManagers;
use App\Models\Alquiler;
use App\Models\AlquilerDisfraz;
use App\Models\AlquilerDisfrazPieza;
use App\Models\Devolucion;
use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class DevolucionResource extends Resource
{
    protected static ?string $model = Devolucion::class;
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
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
            Forms\Components\Section::make('Información de la Devolución')->schema([
                Forms\Components\Hidden::make('alquiler_id')
                    ->default(function () {
                        // Obtén el alquiler_id desde la consulta de la URL
                        return request()->query('alquiler_id');
                    })
                    ->required(),

                Forms\Components\TextInput::make('cliente_name')
                    ->label('Nombre del Cliente')
                    ->default(function ($get) {
                        $alquilerId = $get('alquiler_id');

                        return Alquiler::find($alquilerId)?->cliente->name ?? 'No disponible';
                    })
                    ->disabled(),
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\DatePicker::make('fecha_alquiler')
                        ->label('Fecha de Alquiler')
                        ->default(function ($get) {
                            $alquilerId = $get('alquiler_id');
                            return Alquiler::find($alquilerId)?->fecha_alquiler ?? 'No disponible';
                        })
                        ->disabled(),
                    Forms\Components\DatePicker::make('fecha_devolucion')
                        ->label('Fecha de Devolución')
                        ->default(function ($get) {
                            $alquilerId = $get('alquiler_id');

                            return Alquiler::find($alquilerId)?->fecha_devolucion ?? 'No disponible';
                        })
                        ->disabled(),
                    Forms\Components\DateTimePicker::make('fecha_devolucion_real')
                        ->label('Fecha Actual')
                        ->default(now())
                        ->displayFormat('d/m/Y H:i')
                        ->required(),
                    Forms\Components\TextInput::make('multa_retraso')
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
            ]),
            Forms\Components\Section::make('Piezas Dañadas y/o Perdidas')->schema([
                Repeater::make('devolucionPiezas') //este modelo lo cree para usar repeater  con una tabla pivote
                    ->relationship()
                    ->label(false)
                    ->defaultItems(0)
                    ->reorderable(false)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        Grid::make(13)->schema([
                            Select::make('alquiler_disfraz_id')
                                ->label('Piezas Alquiladas')
                                ->options(function (callable $get) {
                                    // Necesitas saber el alquiler_id para filtrar
                                    $alquilerId = $get('../../alquiler_id');
                                    // Filtrar solo las piezas que pertenecen a ese alquiler
                                    return AlquilerDisfraz::where('alquiler_id', $alquilerId)
                                        ->get()
                                        ->mapWithKeys(function ($item) {
                                            $nombreDisfraz = $item->disfraz->nombre ?? 'Disfraz';
                                            return [
                                                $item->id => "$nombreDisfraz",
                                            ];
                                        });
                                })
                                ->live()
                                ->searchable()
                                ->columnSpan(4)
                                ->reactive()
                                ->required(),
                            Select::make('alquiler_disfraz_pieza_id')
                                ->label('Piezas Alquiladas')
                                ->options(function (Get $get): Collection {
                                    // Necesitas saber el alquiler_id para filtrar
                                    $disfrazId = $get('alquiler_disfraz_id');
                                    return AlquilerDisfrazPieza::where('alquiler_disfraz_id', $disfrazId)
                                        ->get()
                                        ->mapWithKeys(function ($item) {
                                            $nombrePieza = $item->pieza->name ?? 'Pieza';
                                            return [
                                                $item->id => "{$nombrePieza}",
                                            ];
                                        });
                                    // Filtrar solo las piezas que pertenecen a ese alquiler
                                })
                                ->searchable()
                                ->columnSpan(4)
                                ->reactive()
                                ->required(),

                            TextInput::make('cantidad')
                                ->label('Cantidad')
                                ->numeric()
                                ->minValue(1)
                                ->rules([
                                    fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use (
                                        $get
                                    ) {
                                        $piezaIdActual = $get('alquiler_disfraz_pieza_id');
                                        $repeaterState = collect($get('../../devolucionPiezas'));
                                        if (!$piezaIdActual) {
                                            return;
                                        }
                                        $sumaPorPieza = $repeaterState
                                            ->where('alquiler_disfraz_pieza_id', $piezaIdActual)
                                            ->sum('cantidad');
                                        $pieza = \App\Models\AlquilerDisfrazPieza::find($piezaIdActual);
                                        $reservado = $pieza?->cantidad_reservada ?? 0;
                                        if ($sumaPorPieza > $reservado) {
                                            $fail(
                                                "Has superado el límite disponible: $reservado. Estás usando $sumaPorPieza."
                                            );
                                        }
                                    },
                                ])
                                ->columnSpan(2)
                                ->required(),
                            Forms\Components\Radio::make('estado_pieza')
                                ->label('Estado de la pieza')
                                ->options([
                                    'dañado' => 'Dañado',
                                    'perdido' => 'Perdido',
                                ])
                                ->inline()
                                ->inlineLabel(false)
                                ->columnSpan(3)
                                ->required(),
                        ]),
                    ])
                    ->itemLabel(function (array $state): string {
                        $disfraz = AlquilerDisfrazPieza::where('id', $state['alquiler_disfraz_pieza_id'])
                            ->with('pieza')
                            ->first();
                        if (!$disfraz) {
                            return 'Nuevo disfraz';
                        }
                        return "{$disfraz->pieza->name} - Stock: {$disfraz->cantidad_reservada}";
                    })
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
        ];
    }
}
