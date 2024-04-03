<?php

namespace App\Enums\Permissions;

enum Product: string
{
    case PUBLISH = 'publish product';
    case DELETE = 'delete product';
    case EDIT = 'edit product';

    public static function values(): array
    {
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }
}
