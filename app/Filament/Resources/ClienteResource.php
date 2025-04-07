<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Nombre')->required()->maxLength(255),
            Forms\Components\TextInput::make('ci')
                ->label('C.I')
                ->required()
                ->minValue(100000)
                ->maxValue(99999999)
                ->numeric(),
            Forms\Components\TextInput::make('email')->label('Correo electrónico')->nullable()->email()->maxLength(255),
            Forms\Components\TextInput::make('address')->label('Direccion')->required()->maxLength(255),
            Forms\Components\TextInput::make('phone')
                ->label('Número de celular')
                ->tel()
                ->length(8)
                ->required()
                ->rules([
                    'regex:/^[67]\d{7}$/', // empieza en 6 o 7 y tiene 8 dígitos
                ])
                ->numeric(),
            Forms\Components\Hidden::make('user_id')
                ->default(fn() => \Illuminate\Support\Facades\Auth::user()?->id)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('ci')->label('CI')->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Correo electrónico')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('address')->label('Dirección')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('phone')->label('Celular')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Puedes añadir filtros como:
                // SelectFilter::make('state')->options([...]),
                // TernaryFilter::make('has_email')->label('¿Tiene correo?')
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
