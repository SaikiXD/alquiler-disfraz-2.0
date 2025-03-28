<?php

namespace App\Filament\Resources\DisfrazResource\Pages;

use App\Filament\Resources\DisfrazResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDisfraz extends CreateRecord
{
    protected static string $resource = DisfrazResource::class;
    protected function getRedirectUrl(): string
    {
        // Redirige automáticamente a la página de edición después de crear el registro
        return $this->getResource()::getUrl('edit', ['record' => $this->record->getKey()]);
    }
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->modal(false)->label('Registrar Disfraz'), // Solo muestra el botón de crear,
            $this->getCancelFormAction(),
        ];
    }
}
