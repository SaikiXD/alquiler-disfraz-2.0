<?php

namespace App\Filament\Resources\AlquilerResource\Pages;

use App\Filament\Resources\AlquilerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlquilers extends ListRecords
{
    protected static string $resource = AlquilerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
