<?php
declare(strict_types=1);
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MantenimientoEstadoEnum: string implements HasLabel, HasColor
{
    case LIMPIEZA = 'limpieza';
    case REPARACION = 'reparacion';
    case COMPLETADO = 'completado';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LIMPIEZA => 'Limpieza',
            self::REPARACION => 'Reparacion',
            self::COMPLETADO => 'Completado',
        };
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::LIMPIEZA => 'info',
            self::REPARACION => 'danger',
            self::COMPLETADO => 'success',
        };
    }
}
