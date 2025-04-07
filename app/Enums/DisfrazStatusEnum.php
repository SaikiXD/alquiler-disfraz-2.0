<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum DisfrazStatusEnum: string implements HasLabel, HasColor
{
    case DISPONIBLE = 'disponible';
    case NO_DISPONiBLE = 'no_disponible';
    case INCOMPLETO = 'incompleto';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DISPONIBLE => 'Disponible',
            self::NO_DISPONiBLE => 'No Disponible',
            self::INCOMPLETO => 'Incompleto',
        };
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DISPONIBLE => 'success',
            self::NO_DISPONiBLE => 'primary',
            self::INCOMPLETO => 'danger',
        };
    }
}
