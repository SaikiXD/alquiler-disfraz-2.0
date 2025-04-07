<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisfrazResource\Pages;
use App\Filament\Resources\DisfrazResource\RelationManagers;
use App\Models\Disfraz;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\DisfrazStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DisfrazResource extends Resource
{
    protected static ?string $model = Disfraz::class;
    protected static ?string $navigationGroup = 'Inventario';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function getPluralModelLabel(): string
    {
        return 'Disfraces';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Forms\Components\TextInput::make('nombre')
                        ->label('Nombre del Disfraz')
                        ->required()
                        ->placeholder('Ejemplo: Traje de Batman')
                        ->maxLength(100)
                        ->columnSpan([
                            'sm' => 2,
                        ]),
                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(3)
                        ->maxLength(500)
                        ->columnSpan([
                            'sm' => 2,
                        ]),
                    Forms\Components\Select::make('categorias')
                        ->label('Seleccione las Categorías')
                        ->relationship('categorias', 'name')
                        ->multiple()
                        ->required()
                        ->preload()
                        ->searchable()
                        ->optionsLimit(10),
                    Forms\Components\Select::make('genero')
                        ->label('Genero')
                        ->options([
                            'masculino' => 'Masculino',
                            'femenino' => 'Femenino',
                            'unisex' => 'Unisex',
                        ])
                        ->required(),
                ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2),
                Section::make([
                    Forms\Components\FileUpload::make('image_path')
                        ->label('Imagen de Referencia')
                        ->disk('public')
                        ->directory('disfraces')
                        ->image()
                        ->imageEditor()
                        ->panelLayout('integrated') //nose que hace pero centro el texto de dentros
                        ->panelAspectRatio('4:2')
                        ->maxSize(1024)
                        ->required(),
                    Forms\Components\TextInput::make('precio_alquiler')
                        ->label('Precio de Alquiler')
                        ->default(0)
                        ->prefix('Bs')
                        ->reactive()
                        ->numeric()
                        ->required(),
                ])->columnSpan(1),
            ])

            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->label('Nombre')->searchable()->sortable(),
                Tables\Columns\ImageColumn::make('image_path')->label('imagen'),
                Tables\Columns\TextColumn::make('stock_disponible')->label('Stock'),
                Tables\Columns\TextColumn::make('precio_alquiler')
                    ->label('Precio de Alquiler')
                    ->money('BOB', locale: 'es_BO')
                    ->sortable(),
                Tables\Columns\TextColumn::make('categorias.name')->label('Categorías')->badge()->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(
                        fn($state) => $state instanceof DisfrazStatusEnum ? $state->name : (string) $state
                    ),
            ])
            ->filters([
                //
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\ViewAction::make()->color('success')])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [RelationManagers\PiezasRelationManager::class];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDisfrazs::route('/'),
            'create' => Pages\CreateDisfraz::route('/create'),
            'edit' => Pages\EditDisfraz::route('/{record}/edit'),
            'view' => Pages\ViewDisfraz::route('/{record}'),
        ];
    }
}
