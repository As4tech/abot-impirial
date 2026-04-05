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

        // Define granular permissions aligned with app gates
        $permissions = [
            // Products
            'products.view', 'products.create', 'products.update', 'products.delete',
            // Menu Items
            'menu_items.view', 'menu_items.create', 'menu_items.update', 'menu_items.delete',
            // Bookings
            'bookings.view', 'bookings.create', 'bookings.update',
            // Rooms
            'rooms.view', 'rooms.create', 'rooms.update', 'rooms.delete',
            // Room Types
            'room-types.view', 'room-types.create', 'room-types.edit', 'room-types.delete',
            // Inventory
            'inventory.view', 'inventory.manage',
            // Expenses
            'expenses.view', 'expenses.manage',
            // POS and Orders
            'pos.view', 'orders.view',
            // Registers
            'registers.view', 'registers.open', 'registers.close',
            // Reports (granular)
            'reports.sales.view', 'reports.inventory.view', 'reports.bookings.view', 'reports.registers.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Assign permissions to roles (idempotent)
        $admin = Role::findByName('Admin');
        $admin->syncPermissions($permissions);

        $manager = Role::findByName('Manager');
        $manager->syncPermissions([
            'reports.sales.view', 'reports.inventory.view', 'reports.bookings.view', 'reports.registers.view',
            'inventory.view', 'expenses.view', 'expenses.manage',
            'registers.view', 'registers.open', 'registers.close',
            'products.view', 'menu_items.view', 'bookings.view',
            'rooms.view', 'room-types.view',
        ]);

        $cashier = Role::findByName('Cashier');
        $cashier->syncPermissions([
            'pos.view', 'orders.view', 'registers.open', 'registers.close'
        ]);

        $kitchen = Role::findByName('Kitchen Staff');
        $kitchen->syncPermissions(['view_kitchen']);
    }
}
