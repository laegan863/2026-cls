<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main modules based on sidebar
        $modules = [
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'icon' => 'bi bi-speedometer2',
                'route' => 'admin.dashboard',
                'description' => 'Main dashboard overview',
                'order' => 1,
                'is_active' => true,
                'is_coming_soon' => false,
            ],
            [
                'name' => 'Licensing & Permitting',
                'slug' => 'licensing-permitting',
                'icon' => 'bi bi-file-earmark-text',
                'route' => 'admin.licensing',
                'description' => 'Manage licenses and permits',
                'order' => 2,
                'is_active' => true,
                'is_coming_soon' => false,
            ],
            [
                'name' => 'Reports',
                'slug' => 'reports',
                'icon' => 'bi bi-bar-chart',
                'route' => null,
                'description' => 'System reports and analytics',
                'order' => 3,
                'is_active' => true,
                'is_coming_soon' => true,
            ],
            [
                'name' => 'Trade',
                'slug' => 'trade',
                'icon' => 'bi bi-arrow-left-right',
                'route' => null,
                'description' => 'Trade management module',
                'order' => 4,
                'is_active' => true,
                'is_coming_soon' => true,
            ],
            [
                'name' => 'Property Tax',
                'slug' => 'property-tax',
                'icon' => 'bi bi-house',
                'route' => null,
                'description' => 'Property tax management',
                'order' => 5,
                'is_active' => true,
                'is_coming_soon' => true,
            ],
            [
                'name' => 'Accounting',
                'slug' => 'accounting',
                'icon' => 'bi bi-journal-text',
                'route' => null,
                'description' => 'Accounting and financial management',
                'order' => 6,
                'is_active' => true,
                'is_coming_soon' => true,
            ],
            [
                'name' => 'TCEQ / SIR',
                'slug' => 'tceq-sir',
                'icon' => 'bi bi-shield-check',
                'route' => null,
                'description' => 'TCEQ and SIR compliance management',
                'order' => 7,
                'is_active' => true,
                'is_coming_soon' => true,
            ],
            [
                'name' => 'User Management',
                'slug' => 'user-management',
                'icon' => 'bi bi-people',
                'route' => 'admin.users.index',
                'description' => 'Manage system users',
                'order' => 8,
                'is_active' => true,
                'is_coming_soon' => false,
            ],
            [
                'name' => 'Admin Control Center',
                'slug' => 'admin-control-center',
                'icon' => 'bi bi-gear',
                'route' => 'admin.settings',
                'description' => 'System administration and settings',
                'order' => 9,
                'is_active' => true,
                'is_coming_soon' => false,
            ],
        ];

        // Create modules
        foreach ($modules as $moduleData) {
            Module::create($moduleData);
        }

        // Sub-modules for Admin Control Center
        $adminControlCenter = Module::where('slug', 'admin-control-center')->first();
        
        $subModules = [
            [
                'name' => 'Roles',
                'slug' => 'roles',
                'icon' => 'bi bi-shield-shaded',
                'route' => 'admin.roles.index',
                'description' => 'Manage user roles',
                'parent_id' => $adminControlCenter->id,
                'order' => 1,
                'is_active' => true,
                'is_coming_soon' => false,
            ],
            [
                'name' => 'Permissions',
                'slug' => 'permissions',
                'icon' => 'bi bi-key',
                'route' => 'admin.permissions.index',
                'description' => 'Manage role permissions',
                'parent_id' => $adminControlCenter->id,
                'order' => 2,
                'is_active' => true,
                'is_coming_soon' => false,
            ],
            [
                'name' => 'Modules',
                'slug' => 'modules',
                'icon' => 'bi bi-grid-3x3-gap',
                'route' => 'admin.modules.index',
                'description' => 'Manage system modules',
                'parent_id' => $adminControlCenter->id,
                'order' => 3,
                'is_active' => true,
                'is_coming_soon' => false,
            ],
            [
                'name' => 'Permit Types',
                'slug' => 'permit-types',
                'icon' => 'bi bi-card-list',
                'route' => 'admin.permit-types.index',
                'description' => 'Manage permit types',
                'parent_id' => $adminControlCenter->id,
                'order' => 4,
                'is_active' => true,
                'is_coming_soon' => false,
            ],
        ];

        foreach ($subModules as $subModuleData) {
            Module::create($subModuleData);
        }

        // Assign all modules to Admin role with full access
        $admin = Role::where('slug', 'admin')->first();
        $allModules = Module::all();

        if ($admin) {
            foreach ($allModules as $module) {
                $admin->modules()->attach($module->id, [
                    'can_view' => true,
                    'can_create' => true,
                    'can_edit' => true,
                    'can_delete' => true,
                ]);
            }
        }

        // Agent and Client roles start with no modules assigned
        // Admin will assign specific modules through the Role management interface
        // This keeps the roles flexible for dynamic assignment
    }
}
