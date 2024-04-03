<?php

namespace Database\Seeders;

use App\Enums\Permissions\Account;
use App\Enums\Permissions\Category;
use App\Enums\Permissions\Order;
use App\Enums\Permissions\Product;
use App\Enums\Permissions\User;
use App\Enums\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            ...Account::values(),
            ...Category::values(),
            ...Order::values(),
            ...Product::values(),
            ...User::values()
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        if (!Role::where('name', Roles::CUSTOMER->value)->exists()) {
            (Role::create(['name' => Roles::CUSTOMER->value]))->givePermissionTo(
                Account::values()
            );
        }

        if (!Role::where('name', Roles::MODERATOR->value)->exists()) {
            (Role::create(['name' => Roles::MODERATOR->value]))->givePermissionTo(
                Product::values(),
                Category::values()
            );
        }

        if (!Role::where('name', Roles::ADMIN->value)->exists()) {
            (Role::create(['name' => Roles::ADMIN->value]))->givePermissionTo(
                Permission::all()
            );
        }




    }
}
