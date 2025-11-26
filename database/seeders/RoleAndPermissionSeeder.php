<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================
        // ADMIN PERMISSIONS
        // ============================================
        $adminPermissions = [
            'manage users',
            'manage roles',
            'manage permissions',
            'manage products',
            'manage categories',
            'manage stock',
            'view all requests',
            'manage flights',
            'system settings',
            'assign roles',
            'approve any request',
            'view logs',
        ];

        // ============================================
        // PMU INVENTORY PERSONNEL PERMISSIONS
        // ============================================
        $inventoryPersonnelPermissions = [
            'view products',
            'create products',
            'update products',
            'add stock',
            'issue stock',
            'process returns',
            'view stock levels',
            'generate stock movement reports',
        ];

        // ============================================
        // PMU INVENTORY SUPERVISOR PERMISSIONS
        // ============================================
        $inventorySupervisorPermissions = [
            'approve products',
            'approve stock movements',
            'approve stock entries',
            'verify stock movement',
            'view inventory reports',
            'view stock levels',
            'block allow product usage',
            'edit product records',
            'view incoming requests from catering staff',
            'approve deny catering requests',
        ];

        // ============================================
        // CATERING INCHARGE PERMISSIONS
        // ============================================
        $cateringInchargePermissions = [
            'view all catering requests',
            'approve catering staff requests',
            'receive products from inventory', // Approve product receipts from Inventory Personnel
            'approve product receipts', // Approve received products
            'oversee catering stock', // View and manage catering stock levels
            'request stock from PMU',
            'view inventory usage',
            'view dispatch reports',
            'view product categories',
            'return receive items from flights',
        ];

        // ============================================
        // CATERING STAFF PERMISSIONS
        // ============================================
        $cateringStaffPermissions = [
            'create catering request',
            'view own catering requests',
            'receive approved items',
            'record items used',
            'return unused items',
            'view product list',
            'view flight assigned requests',
        ];

        // ============================================
        // RAMP DISPATCHER PERMISSIONS
        // ============================================
        $rampDispatcherPermissions = [
            'view approved orders',
            'prepare dispatch manifest',
            'mark items as dispatched',
            'handover to flight crew',
            'view dispatch reports',
            'verify quantities before loading',
        ];

        // ============================================
        // SECURITY STAFF PERMISSIONS
        // ============================================
        $securityStaffPermissions = [
            'authenticate requests',
            'authenticate orders',
            'match request vs dispatch',
            'approve final dispatch security check',
            'block suspicious dispatch',
            'view dispatch logs',
        ];

        // ============================================
        // CABIN CREW PERMISSIONS
        // ============================================
        $cabinCrewPermissions = [
            'receive goods from dispatcher',
            'record items used during flight',
            'record remaining items',
            'submit usage report',
            'view flight details assigned to them',
        ];

        // ============================================
        // FLIGHT PURSER PERMISSIONS
        // ============================================
        $flightPurserPermissions = [
            'view flight schedule',
            'view flight passenger capacity',
            'view flight products assigned',
            'approve cabin crew usage report',
            'finalize flight report',
            'view all usage per flight',
            'submit final flight consumption',
        ];

        // Create all permissions
        $allPermissions = array_merge($adminPermissions, $inventoryPersonnelPermissions, $inventorySupervisorPermissions, $cateringInchargePermissions, $cateringStaffPermissions, $rampDispatcherPermissions, $securityStaffPermissions, $cabinCrewPermissions, $flightPurserPermissions);
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ============================================
        // CREATE ADMIN ROLE
        // ============================================
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions($adminPermissions);

        // ============================================
        // CREATE INVENTORY PERSONNEL ROLE
        // ============================================
        $inventoryPersonnelRole = Role::firstOrCreate(['name' => 'Inventory Personnel']);
        $inventoryPersonnelRole->syncPermissions($inventoryPersonnelPermissions);

        // ============================================
        // CREATE INVENTORY SUPERVISOR ROLE
        // ============================================
        $inventorySupervisorRole = Role::firstOrCreate(['name' => 'Inventory Supervisor']);
        // Supervisor has their own permissions + can view what personnel do
        $supervisorAllPermissions = array_merge($inventorySupervisorPermissions, [
            'view products',
            'view stock levels',
            'generate stock movement reports',
        ]);
        $inventorySupervisorRole->syncPermissions($supervisorAllPermissions);

        // ============================================
        // CREATE CATERING INCHARGE ROLE
        // ============================================
        $cateringInchargeRole = Role::firstOrCreate(['name' => 'Catering Incharge']);
        $cateringInchargeRole->syncPermissions($cateringInchargePermissions);

        // ============================================
        // CREATE CATERING STAFF ROLE
        // ============================================
        $cateringStaffRole = Role::firstOrCreate(['name' => 'Catering Staff']);
        $cateringStaffRole->syncPermissions($cateringStaffPermissions);

        // ============================================
        // CREATE RAMP DISPATCHER ROLE
        // ============================================
        $rampDispatcherRole = Role::firstOrCreate(['name' => 'Ramp Dispatcher']);
        $rampDispatcherRole->syncPermissions($rampDispatcherPermissions);

        // ============================================
        // CREATE SECURITY STAFF ROLE
        // ============================================
        $securityStaffRole = Role::firstOrCreate(['name' => 'Security Staff']);
        $securityStaffRole->syncPermissions($securityStaffPermissions);

        // ============================================
        // CREATE CABIN CREW ROLE
        // ============================================
        $cabinCrewRole = Role::firstOrCreate(['name' => 'Cabin Crew']);
        $cabinCrewRole->syncPermissions($cabinCrewPermissions);

        // ============================================
        // CREATE FLIGHT PURSER ROLE
        // ============================================
        $flightPurserRole = Role::firstOrCreate(['name' => 'Flight Purser']);
        $flightPurserRole->syncPermissions($flightPurserPermissions);

        // ============================================
        // CREATE ADMIN USER
        // ============================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@inflightcatering.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin@123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Admin');

        // ============================================
        // CREATE INVENTORY PERSONNEL USER
        // ============================================
        $inventoryPersonnel = User::firstOrCreate(
            ['email' => 'inventory@inflightcatering.com'],
            [
                'name' => 'Inventory Personnel',
                'password' => Hash::make('Inventory@123'),
                'email_verified_at' => now(),
            ]
        );
        $inventoryPersonnel->assignRole('Inventory Personnel');

        // ============================================
        // CREATE INVENTORY SUPERVISOR USER
        // ============================================
        $inventorySupervisor = User::firstOrCreate(
            ['email' => 'supervisor@inflightcatering.com'],
            [
                'name' => 'Inventory Supervisor',
                'password' => Hash::make('Supervisor@123'),
                'email_verified_at' => now(),
            ]
        );
        $inventorySupervisor->assignRole('Inventory Supervisor');

        // ============================================
        // CREATE CATERING INCHARGE USER
        // ============================================
        $cateringIncharge = User::firstOrCreate(
            ['email' => 'catering@inflightcatering.com'],
            [
                'name' => 'Catering Incharge',
                'password' => Hash::make('Catering@123'),
                'email_verified_at' => now(),
            ]
        );
        $cateringIncharge->assignRole('Catering Incharge');

        // ============================================
        // CREATE CATERING STAFF USER
        // ============================================
        $cateringStaff = User::firstOrCreate(
            ['email' => 'staff@inflightcatering.com'],
            [
                'name' => 'Catering Staff',
                'password' => Hash::make('Staff@123'),
                'email_verified_at' => now(),
            ]
        );
        $cateringStaff->assignRole('Catering Staff');

        // ============================================
        // CREATE RAMP DISPATCHER USER
        // ============================================
        $rampDispatcher = User::firstOrCreate(
            ['email' => 'dispatcher@inflightcatering.com'],
            [
                'name' => 'Ramp Dispatcher',
                'password' => Hash::make('Dispatcher@123'),
                'email_verified_at' => now(),
            ]
        );
        $rampDispatcher->assignRole('Ramp Dispatcher');

        // ============================================
        // CREATE SECURITY STAFF USER
        // ============================================
        $securityStaff = User::firstOrCreate(
            ['email' => 'security@inflightcatering.com'],
            [
                'name' => 'Security Staff',
                'password' => Hash::make('Security@123'),
                'email_verified_at' => now(),
            ]
        );
        $securityStaff->assignRole('Security Staff');

        // ============================================
        // CREATE CABIN CREW USER
        // ============================================
        $cabinCrew = User::firstOrCreate(
            ['email' => 'cabin@inflightcatering.com'],
            [
                'name' => 'Cabin Crew',
                'password' => Hash::make('Cabin@123'),
                'email_verified_at' => now(),
            ]
        );
        $cabinCrew->assignRole('Cabin Crew');

        // ============================================
        // CREATE FLIGHT PURSER USER
        // ============================================
        $flightPurser = User::firstOrCreate(
            ['email' => 'purser@inflightcatering.com'],
            [
                'name' => 'Flight Purser',
                'password' => Hash::make('Purser@123'),
                'email_verified_at' => now(),
            ]
        );
        $flightPurser->assignRole('Flight Purser');

        // ============================================
        // OUTPUT SUCCESS MESSAGES
        // ============================================
        $this->command->info('');
        $this->command->info('✅ Users created successfully!');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('👤 ADMIN USER:');
        $this->command->info('   Email:    admin@inflightcatering.com');
        $this->command->info('   Password: Admin@123');
        $this->command->info('');
        $this->command->info('👤 INVENTORY PERSONNEL:');
        $this->command->info('   Email:    inventory@inflightcatering.com');
        $this->command->info('   Password: Inventory@123');
        $this->command->info('');
        $this->command->info('👤 INVENTORY SUPERVISOR:');
        $this->command->info('   Email:    supervisor@inflightcatering.com');
        $this->command->info('   Password: Supervisor@123');
        $this->command->info('');
        $this->command->info('👤 CATERING INCHARGE:');
        $this->command->info('   Email:    catering@inflightcatering.com');
        $this->command->info('   Password: Catering@123');
        $this->command->info('');
        $this->command->info('👤 CATERING STAFF:');
        $this->command->info('   Email:    staff@inflightcatering.com');
        $this->command->info('   Password: Staff@123');
        $this->command->info('');
        $this->command->info('👤 RAMP DISPATCHER:');
        $this->command->info('   Email:    dispatcher@inflightcatering.com');
        $this->command->info('   Password: Dispatcher@123');
        $this->command->info('');
        $this->command->info('👤 SECURITY STAFF:');
        $this->command->info('   Email:    security@inflightcatering.com');
        $this->command->info('   Password: Security@123');
        $this->command->info('');
        $this->command->info('👤 CABIN CREW:');
        $this->command->info('   Email:    cabin@inflightcatering.com');
        $this->command->info('   Password: Cabin@123');
        $this->command->info('');
        $this->command->info('👤 FLIGHT PURSER:');
        $this->command->info('   Email:    purser@inflightcatering.com');
        $this->command->info('   Password: Purser@123');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
