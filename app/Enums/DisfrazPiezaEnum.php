<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
enum DisfrazPiezaEnum: string implements HasLabel, HasColor
{
    case DISPONIBLE = 'disponible';
    case RESERVADO = 'reservado';
    case DAÑADO = 'dañado';
    case PERDIDO = 'perdido';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DISPONIBLE => 'Disponible',
            self::RESERVADO => 'Reservado',
            self::DAÑADO => 'Dañado',
            self::PERDIDO => 'Perdido',
        };
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DISPONIBLE => 'gray',
        };
    }
}
