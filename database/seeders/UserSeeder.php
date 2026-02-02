<?php

namespace Database\Seeders;

use App\Models\SystemUser;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles/permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Ensure role exists for admin guard
        Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin',
        ]);

        $user = SystemUser::updateOrCreate(
            ['email' => 'info@laughindustries.com'],
            [
                "name" => "Default Administrator",
                "password" => bcrypt('1234567'),
                "status" => true,
                "type" => 'owner'
            ]
        );

        // Assign role (guard-aware)
        $user->assignRole('Super Admin');
    }
}
