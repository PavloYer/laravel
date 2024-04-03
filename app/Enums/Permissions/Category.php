<?php

namespace App\Enums\Permissions;

enum Category: string
{
    case PUBLISH = 'publish category';
    case DELETE = 'delete category';
    case EDIT = 'edit category';

    public static function values(): array
    {
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }
}
