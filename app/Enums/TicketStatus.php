<?php

namespace App\Enums;

enum TicketStatus: string
{
    case ACTIVE = 'A';
    case COMPLETED = 'C';
    case HOLD = 'H';
    case CANCELED = 'X';

    public static function values(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    }
}
