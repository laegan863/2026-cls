<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
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
        // Seed roles first, then modules
        $this->call([
            RoleSeeder::class,
            ModuleSeeder::class,
        ]);

        // Get the Admin role
        $adminRole = Role::where('slug', 'admin')->first();

        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => 'password', // Use a secure password
            'role_id' => $adminRole?->id,
            'is_active' => true,
        ]);
    }
}
