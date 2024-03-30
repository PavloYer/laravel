<?php

namespace App\Enums\Permissions;

enum User: string
{
    case DELETE = 'delete user';
    case EDIT = 'edit user';
}
