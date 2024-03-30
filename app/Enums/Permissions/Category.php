<?php

namespace App\Enums\Permissions;

enum Product: string
{
    case publish = 'publish product';
    case delete = 'delete product';
    case update = 'update product';
}
