<?php

namespace App\Filament\Resources\AlquilerResource\Pages;

use App\Filament\Resources\AlquilerResource;
use App\Models\Alquiler;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAlquiler extends CreateRecord
{
    protected static string $resource = AlquilerResource::class;
}
