<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1️⃣ FLIGHT INFORMATION PERMISSIONS
        $flightInfoPermissions = [
            'view flight schedule',
            'view flight status',
            'update flight status',
            'update flight estimated time',
            'view aircraft assignment',
            'view flight route',
        ];

        // 2️⃣ DISPATCH & OPERATIONS PERMISSIONS
        $dispatchOperationsPermissions = [
            'create flight dispatch record',
            'update dispatch details',
            'view fuel status',
            'confirm fuel status',
            'confirm crew readiness',
            'confirm catering received',
            'confirm baggage loaded',
            'send operational notes',
            'send delay reason report',
        ];

        // 3️⃣ MESSAGING & COMMUNICATION PERMISSIONS
        $messagingPermissions = [
            'view cabin crew messages',
            'view ramp dispatcher messages',
            'view catering team messages',
            'send message to cabin crew',
            'send message to ramp dispatcher',
            'send message to catering team',
            'add notes to request',
            'view request communication history',
        ];

        // 4️⃣ ADDITIONAL HELPFUL PERMISSIONS
        $additionalPermissions = [
            'view flight dispatcher dashboard',
            'view all flight dispatches',
            'view flight readiness checklist',
            'generate dispatch report',
            'view flight operations overview',
        ];

        // Combine all permissions
        $allNewPermissions = array_merge(
            $flightInfoPermissions,
            $dispatchOperationsPermissions,
            $messagingPermissions,
            $additionalPermissions
        );

        // Create all permissions
        foreach ($allNewPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign to Flight Dispatcher role
        $flightDispatcherRole = Role::firstOrCreate(['name' => 'Flight Dispatcher']);
        $flightDispatcherRole->givePermissionTo($allNewPermissions);

        // Also keep existing permissions
        $existingPermissions = [
            'view requests',
            'inspect requests for errors',
            'assess flight readiness',
            'forward requests to flight purser',
            'view awaiting assessment requests',
            'view flight requirements',
            'view flight products assigned',
            'view dispatch reports',
            'comment on request',
            'recommend dispatch to flight operations',
        ];

        foreach ($existingPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $flightDispatcherRole->givePermissionTo($existingPermissions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            // Flight Info
            'view flight schedule',
            'view flight status',
            'update flight status',
            'update flight estimated time',
            'view aircraft assignment',
            'view flight route',
            // Dispatch Operations
            'create flight dispatch record',
            'update dispatch details',
            'view fuel status',
            'confirm fuel status',
            'confirm crew readiness',
            'confirm catering received',
            'confirm baggage loaded',
            'send operational notes',
            'send delay reason report',
            // Messaging
            'view cabin crew messages',
            'view ramp dispatcher messages',
            'view catering team messages',
            'send message to cabin crew',
            'send message to ramp dispatcher',
            'send message to catering team',
            'add notes to request',
            'view request communication history',
            // Additional
            'view flight dispatcher dashboard',
            'view all flight dispatches',
            'view flight readiness checklist',
            'generate dispatch report',
            'view flight operations overview',
        ];

        foreach ($permissions as $permission) {
            Permission::where('name', $permission)->delete();
        }
    }
};
