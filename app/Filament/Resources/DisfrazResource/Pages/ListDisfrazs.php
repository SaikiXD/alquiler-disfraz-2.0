<?php

namespace App\Filament\Resources\DisfrazResource\Pages;

use App\Filament\Resources\DisfrazResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisfrazs extends ListRecords
{
    protected static string $resource = DisfrazResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
