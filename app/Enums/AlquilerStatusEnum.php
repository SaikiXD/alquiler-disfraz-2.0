<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum AlquilerStatusEnum: string implements HasLabel, HasColor
{
    case PENDIENTE = 'pendiente';

    case ALQUILADO = 'alquilado';

    case FINALIZADO = 'finalizado';

    case CANCELADO = 'cancelado';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente',
            self::ALQUILADO => 'Alquilado',
            self::FINALIZADO => 'Finalizado',
            self::CANCELADO => 'Cancelado',
        };
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDIENTE => 'gray',
            self::ALQUILADO => 'green',
        };
    }
}
