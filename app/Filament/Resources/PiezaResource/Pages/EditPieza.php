<?php

namespace App\Filament\Resources\PiezaResource\Pages;

use App\Filament\Resources\PiezaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPieza extends EditRecord
{
    protected static string $resource = PiezaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
