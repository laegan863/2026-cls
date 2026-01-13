<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder sets up the proper permission-module-role relationships:
     * - Admin: Full access to all modules and all permissions
     * - Agent: Access to Dashboard (limited) and Licensing & Permitting
     * - Client: Access to Dashboard (Payment/Renewal Queue only) and Licensing & Permitting (View only)
     */
    public function run(): void
    {
        // Get roles
        $admin = Role::where('slug', 'admin')->first();
        $agent = Role::where('slug', 'agent')->first();
        $client = Role::where('slug', 'client')->first();

        // Get all permissions
        $permissions = Permission::all();

        // Dashboard permissions
        $dashboardViewOverdue = Permission::where('name', 'View Overdue, Active Licenses and Renewal Open')->first();
        $dashboardViewStatus = Permission::where('name', 'View the Renewal Status, Billing Status, Due Date Distribution')->first();
        $dashboardViewWorkflow = Permission::where('name', 'View workflow statuses')->first();
        $dashboardPaymentQueue = Permission::where('name', 'Payment / Renewal Queue')->first();

        // Licensing permissions
        $licensingCreate = Permission::where('name', 'Create')->where('module', 'Licensing & Permitting')->first();
        $licensingEdit = Permission::where('name', 'Edit')->where('module', 'Licensing & Permitting')->first();
        $licensingView = Permission::where('name', 'View')->where('module', 'Licensing & Permitting')->first();
        $licensingDelete = Permission::where('name', 'Delete')->where('module', 'Licensing & Permitting')->first();

        // Get modules
        $dashboard = Module::where('name', 'Dashboard')->first();
        $licensing = Module::where('name', 'Licensing & Permitting')->first();

        // ===== ADMIN ROLE =====
        // Admin gets all permissions
        if ($admin) {
            $admin->permissions()->sync($permissions->pluck('id')->toArray());
        }

        // ===== AGENT ROLE =====
        if ($agent) {
            $agentPermissions = collect();
            
            // Dashboard: View Overdue, View Status (no workflow view for agent)
            if ($dashboardViewOverdue) $agentPermissions->push($dashboardViewOverdue->id);
            if ($dashboardViewStatus) $agentPermissions->push($dashboardViewStatus->id);
            if ($dashboardPaymentQueue) $agentPermissions->push($dashboardPaymentQueue->id);
            
            // Licensing: All except maybe delete
            if ($licensingCreate) $agentPermissions->push($licensingCreate->id);
            if ($licensingEdit) $agentPermissions->push($licensingEdit->id);
            if ($licensingView) $agentPermissions->push($licensingView->id);
            if ($licensingDelete) $agentPermissions->push($licensingDelete->id);
            
            $agent->permissions()->sync($agentPermissions->toArray());
        }

        // ===== CLIENT ROLE =====
        if ($client) {
            $clientPermissions = collect();
            
            // Dashboard: Only Payment/Renewal Queue
            if ($dashboardPaymentQueue) $clientPermissions->push($dashboardPaymentQueue->id);
            
            // Licensing: Only View
            if ($licensingView) $clientPermissions->push($licensingView->id);
            
            $client->permissions()->sync($clientPermissions->toArray());
        }

        $this->command->info('Role permissions have been set up successfully!');
        $this->command->table(
            ['Role', 'Permissions'],
            [
                ['Admin', $admin ? $admin->permissions()->count() . ' permissions' : 'N/A'],
                ['Agent', $agent ? $agent->permissions()->count() . ' permissions' : 'N/A'],
                ['Client', $client ? $client->permissions()->count() . ' permissions' : 'N/A'],
            ]
        );
    }
}
