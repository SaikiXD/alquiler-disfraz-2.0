<?php

namespace App\Filament\Resources\AlquilerResource\Pages;

use App\Filament\Resources\AlquilerResource;
use Filament\Resources\Pages\Page;

class DevolucionAlquiler extends Page
{
    protected static string $resource = AlquilerResource::class;

    protected static string $view = 'filament.pages.alquiler-resource.devolucion-alquiler';
}
