<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            ProductSeeder::class,
        ]);

        // Create a default admin user and assign role (idempotent)
        $user = User::firstOrCreate(
            ['email' => 'admin@abot-imperial.test'],
            ['name' => 'Admin User', 'password' => 'password']
        );

        $adminRole = Role::findOrCreate('Admin', 'web');
        $user->assignRole($adminRole);
    }
}
