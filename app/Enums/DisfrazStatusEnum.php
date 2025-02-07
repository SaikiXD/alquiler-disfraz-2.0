<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum DisfrazStatusEnum: string implements HasLabel, HasColor
{
    case DISPONIBLE = 'disponible';
    case INCOMPLETO = 'incompleto';
    case NO_DISPONIBLE = 'no_disponible';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DISPONIBLE => 'Disponible',
            self::INCOMPLETO => 'Incompleto',
            self::NO_DISPONIBLE => 'No Disponible',
        };
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DISPONIBLE => 'gray',
        };
    }
}
