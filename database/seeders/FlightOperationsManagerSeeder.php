<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FlightOperationsManagerSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // ============================================
        // FLIGHT OPERATIONS MANAGER PERMISSIONS
        // ============================================
        $flightOperationsPermissions = [
            'create flights',
            'edit flights',
            'delete flights',
            'view flights',
            'view settings',
            'manage flight schedules',
            'view flight details',
            'update flight status',
            'view flight statistics',
        ];

        // Create all permissions
        foreach ($flightOperationsPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ============================================
        // CREATE FLIGHT OPERATIONS MANAGER ROLE
        // ============================================
        $flightOpsRole = Role::firstOrCreate(['name' => 'Flight Operations Manager']);
        $flightOpsRole->syncPermissions($flightOperationsPermissions);

        // ============================================
        // CREATE TEST USER
        // ============================================
        $user = User::firstOrCreate(
            ['email' => 'flightops@inflightcatering.com'],
            [
                'name' => 'Flight Operations Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign role to user
        $user->syncRoles(['Flight Operations Manager']);

        echo "✅ Flight Operations Manager role created with " . count($flightOperationsPermissions) . " permissions\n";
        echo "✅ Test user created: flightops@inflightcatering.com (password: password)\n";
    }
}
