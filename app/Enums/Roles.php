<?php

namespace App\Enums;

enum Roles: string
{
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';
    case CUSTOMER = 'customer';

    public static function values(): array
    {
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }
}
