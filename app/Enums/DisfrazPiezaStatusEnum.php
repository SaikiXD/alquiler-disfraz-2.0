<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
enum DisfrazPiezaStatusEnum: string implements HasLabel, HasColor
{
    case DISPONIBLE = 'disponible';
    case ALQUILADO = 'alquilado';
    case RETIRADO = 'retirado';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DISPONIBLE => 'Disponible',
            self::ALQUILADO => 'Alquilado',
            self::RETIRADO => 'Retirado',
        };
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DISPONIBLE => 'success',
            self::ALQUILADO => 'warning',
            self::RETIRADO => 'secondary',
        };
    }
}
