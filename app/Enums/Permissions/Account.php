<?php

namespace App\Enums\Permissions;

enum Account: string
{
    case DELETE = 'delete account';
    case EDIT = 'edit account';

    public static function values(): array
    {
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }
}
