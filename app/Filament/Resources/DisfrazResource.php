<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisfrazResource\Pages;
use App\Filament\Resources\DisfrazResource\RelationManagers;
use App\Models\Disfraz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DisfrazResource extends Resource
{
    protected static ?string $model = Disfraz::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Nombre')->required()->maxLength(255),
            Forms\Components\Textarea::make('description')->label('Descripcion')->columnSpanFull(),
            Forms\Components\Select::make('gender')
                ->label('Género')
                ->options([
                    'masculino' => 'Masculino',
                    'femenino' => 'Femenino',
                    'unisex' => 'Unisex',
                ])
                ->required(),
            Forms\Components\FileUpload::make('image_path')
                ->label('Imagen de Referencia')
                ->image()
                ->imageEditor()
                ->required(),
            Forms\Components\Select::make('categorias')
                ->relationship('categorias', 'name')
                ->multiple()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('price')->label('Precio Recomendado')->required()->numeric()->prefix('$'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\ImageColumn::make('image_path'),
                Tables\Columns\TextColumn::make('price')->money()->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(
                        fn($state) => $state instanceof \App\Enums\DisfrazStatusEnum ? $state->name : (string) $state
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
            ->actions([Tables\Actions\EditAction::make()])
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
        ];
    }
}
