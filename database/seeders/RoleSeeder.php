<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full access to all modules and functionalities of the system',
                'is_active' => true,
            ],
            [
                'name' => 'Agent',
                'slug' => 'agent',
                'description' => 'Admin can assign specific modules and functionalities',
                'is_active' => true,
            ],
            [
                'name' => 'Client',
                'slug' => 'client',
                'description' => 'Admin can assign specific modules and functionalities',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
