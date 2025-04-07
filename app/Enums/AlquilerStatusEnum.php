<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum AlquilerStatusEnum: string implements HasLabel, HasColor
{
    case ALQUILADO = 'alquilado';
    case FINALIZADO = 'finalizado';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ALQUILADO => 'Alquilado',
            self::FINALIZADO => 'Finalizado',
        };
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ALQUILADO => 'success',
            self::FINALIZADO => 'secondary',
        };
    }
}
