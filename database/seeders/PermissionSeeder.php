<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions dynamically
        $permissions = [
            // User Management
            'view users', 'create users', 'edit users', 'delete users',
            
            // Role Management
            'view roles', 'edit roles', 'assign roles',
            
            // Product Management
            'view products', 'create products', 'edit products', 'delete products',
            
            // Category Management
            'view categories', 'create categories', 'edit categories', 'delete categories',
            
            // Stock Management
            'view stock', 'create stock movements', 'edit stock movements', 'delete stock movements',
            'manage incoming stock', 'manage stock issues', 'manage stock returns',
            
            // Flight Management
            'view flights', 'create flights', 'edit flights', 'delete flights',
            
            // Request Management
            'view requests', 'create requests', 'edit requests', 'delete requests',
            'approve requests', 'reject requests',
            'view pending requests', 'view approved requests', 'view rejected requests',
            
            // Audit Logs
            'view audit logs', 'delete audit logs',
            
            // System Settings
            'view settings', 'edit settings', 'manage backup',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles with specific permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer']);

        // Admin gets all permissions
        $adminRole->syncPermissions(Permission::all());

        // Manager permissions
        $managerRole->syncPermissions([
            'view users',
            'view products', 'create products', 'edit products',
            'view categories', 'create categories', 'edit categories',
            'view stock', 'create stock movements', 'manage incoming stock', 'manage stock issues', 'manage stock returns',
            'view flights', 'create flights', 'edit flights',
            'view requests', 'create requests', 'approve requests', 'reject requests',
            'view pending requests', 'view approved requests', 'view rejected requests',
            'view audit logs',
        ]);

        // Staff permissions
        $staffRole->syncPermissions([
            'view products', 'view categories',
            'view stock', 'create stock movements',
            'view flights',
            'view requests', 'create requests', 'view pending requests',
        ]);

        // Viewer permissions
        $viewerRole->syncPermissions([
            'view products', 'view categories',
            'view stock', 'view flights', 'view requests',
        ]);

        $this->command->info('Permissions and roles created successfully!');
        $this->command->info('Total Permissions: ' . Permission::count());
        $this->command->info('Total Roles: ' . Role::count());
    }
}
