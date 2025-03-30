<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum DisfrazStatusEnum: string implements HasLabel, HasColor
{
    case DISPONIBLE = 'disponible';
    case RESERVADO = 'reservado';
    case INCOMPLETO = 'incompleto';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DISPONIBLE => 'Disponible',
            self::RESERVADO => 'Reservado',
            self::INCOMPLETO => 'Incompleto',
        };
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DISPONIBLE => 'success',
            self::RESERVADO => 'primary',
            self::INCOMPLETO => 'danger',
        };
    }
}
