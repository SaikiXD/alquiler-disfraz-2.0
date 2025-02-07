<?php

namespace App\Filament\Resources\DisfrazResource\Pages;

use App\Filament\Resources\DisfrazResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisfraz extends EditRecord
{
    protected static string $resource = DisfrazResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
