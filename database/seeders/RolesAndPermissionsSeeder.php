<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            'Admin',
            'Manager',
            'Cashier',
            'Kitchen Staff',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Create base permissions
        $permissions = [
            'create_order',
            'manage_inventory',
            'view_reports',
            'manage_roles',
            'manage_permissions',
            'manage_users',
            'view_kitchen',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Assign permissions to roles (idempotent)
        $admin = Role::findByName('Admin');
        $admin->givePermissionTo($permissions);

        $manager = Role::findByName('Manager');
        $manager->syncPermissions(['create_order','manage_inventory','view_reports']);

        $cashier = Role::findByName('Cashier');
        $cashier->syncPermissions(['create_order']);

        $kitchen = Role::findByName('Kitchen Staff');
        $kitchen->syncPermissions(['view_kitchen']);
    }
}
