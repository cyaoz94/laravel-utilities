<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Cyaoz94\LaravelUtilities\Models\AdminUser;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $guardName = 'admin';

        $permissions = [
            // your permissions here.
            // 'admin-user.create',
            // 'admin-user.read',
            // 'admin-user.update',
            // 'admin-user.delete',
            // 'role.create',
            // 'role.read',
            // 'role.update',
            // 'role.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, $guardName);
        }

        // remove permissions not defined in $permissions
        Permission::whereNotIn('name', $permissions)->where('guard_name', $guardName)->delete();

        $superAdminRole = Role::findOrCreate('Super Admin', $guardName);
        $superAdminRole->givePermissionTo(Permission::all());

        $superAdminUser = AdminUser::firstOrCreate(
            [
                'id' => 1,
            ],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password')
            ]
        );

        $superAdminUser->assignRole($superAdminRole);
    }
}
