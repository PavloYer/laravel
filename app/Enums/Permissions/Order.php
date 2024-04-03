<?php

namespace App\Enums\Permissions;

enum Order: string
{
    case DELETE = 'delete order';
    case EDIT = 'edit order';

    public static function values(): array
    {
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }
}
