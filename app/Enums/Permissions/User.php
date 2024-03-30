<?php

namespace App\Enums\Permissions;

enum Category: string
{
    case PUBLISH = 'publish category';
    case DELETE = 'delete category';
    case EDIT = 'edit category';
}
