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
        // Load permissions from centralized config and create them
        $allPermissions = config('permissions.list', []);
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Load role => permission mapping from config and sync roles dynamically
        $roleMap = config('role_permission_map', []);
        foreach ($roleMap as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            // Ensure only existing permissions are synced to avoid errors
            $valid = array_filter($permissions, fn($p) => in_array($p, $allPermissions));
            $role->syncPermissions($valid);
        }

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
                'password' => Hash::make('dispatcher@123'),
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
        // CREATE FLIGHT DISPATCHER USER
        // ============================================
        $flightDispatcher = User::firstOrCreate(
            ['email' => 'flight.dispatcher@inflightcatering.com'],
            [
                'name' => 'Flight Dispatcher',
                'password' => Hash::make('Flight@123'),
                'email_verified_at' => now(),
            ]
        );
        $flightDispatcher->assignRole('Flight Dispatcher');

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
        $this->command->info('');
        $this->command->info('👤 FLIGHT DISPATCHER:');        
        $this->command->info('   Email:    flight.dispatcher@inflightcatering.com');
        $this->command->info('   Password: Flight@123');
        $this->command->info('👤 FlightOperations:');
        $this->command->info('   Email:    flight.operations@inflightcatering.com');
        $this->command->info('   Password: Password');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
