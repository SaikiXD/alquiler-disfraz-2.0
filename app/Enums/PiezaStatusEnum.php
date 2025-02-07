<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum PiezaStatusEnum: string implements HasLabel, HasColor
{
    case DISPONIBLE = 'disponible';
    case DAÑADO = 'dañado';
    case PERDIDO = 'perdido';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DISPONIBLE => 'Disponible',
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
