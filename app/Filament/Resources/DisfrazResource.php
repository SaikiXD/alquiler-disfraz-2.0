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

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function getPluralModelLabel(): string
    {
        return 'Disfraces';
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Split::make([
                Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre del Disfraz')
                        ->required()
                        ->placeholder('Ejemplo: Traje de Batman')
                        ->maxLength(100),
                    Forms\Components\Textarea::make('description')->label('Descripción')->rows(3)->maxLength(500),
                    Forms\Components\Select::make('categorias')
                        ->label('Seleccione las Categorías')
                        ->relationship('categorias', 'name')
                        ->multiple()
                        ->preload()
                        ->required()
                        ->searchable()
                        ->optionsLimit(10),
                ]),
            ])->from('md'),
            Split::make([
                Section::make('Detalles de Precio e Imagen')->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('Precio Sugerido')
                        ->readOnly()
                        ->default(0)
                        ->prefix('Bs')
                        ->hintIcon(
                            'heroicon-m-question-mark-circle',
                            tooltip: 'El precio se actualizará automáticamente en función de las piezas agregadas.'
                        )
                        ->hintColor('primary'),

                    Forms\Components\FileUpload::make('image_path')
                        ->label('Imagen de Referencia')
                        ->image()
                        ->imageEditor()
                        ->maxSize(1024)
                        ->required(),
                ]),
            ])->from('md'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                Tables\Columns\ImageColumn::make('image_path')->label('imagen'),
                Tables\Columns\TextColumn::make('stock_disponible')->label('Stock'),
                Tables\Columns\TextColumn::make('price')->label('precio')->money('BOB', locale: 'es_BO')->sortable(),
                Tables\Columns\TextColumn::make('categorias.name')
                    ->label('Categorías')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(
                        fn($state) => $state instanceof DisfrazStatusEnum ? $state->name : (string) $state
                    ),

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
