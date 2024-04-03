<?php

namespace App\Enums\Permissions;

enum User: string
{
    case DELETE = 'delete user';
    case EDIT = 'edit user';

    public static function values(): array
    {
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }
}
