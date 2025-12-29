<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Permission;

echo "\n=== FIXING USER PERMISSIONS ===\n\n";

// Fix Catering Incharge permissions
$cateringIncharge = User::role('Catering Incharge')->first();
if ($cateringIncharge) {
    echo "Fixing Catering Incharge permissions...\n";
    
    $permission = Permission::firstOrCreate(['name' => 'approve deny catering requests']);
    if (!$cateringIncharge->hasPermissionTo('approve deny catering requests')) {
        $cateringIncharge->givePermissionTo('approve deny catering requests');
        echo "  ✓ Added 'approve deny catering requests' permission\n";
    } else {
        echo "  - Already has 'approve deny catering requests' permission\n";
    }
}

// Fix Inventory Personnel permissions  
$inventoryPersonnel = User::role('Inventory Personnel')->first();
if ($inventoryPersonnel) {
    echo "\nFixing Inventory Personnel permissions...\n";
    
    $permission = Permission::firstOrCreate(['name' => 'issue products to catering staff']);
    if (!$inventoryPersonnel->hasPermissionTo('issue products to catering staff')) {
        $inventoryPersonnel->givePermissionTo('issue products to catering staff');
        echo "  ✓ Added 'issue products to catering staff' permission\n";
    } else {
        echo "  - Already has 'issue products to catering staff' permission\n";
    }
}

echo "\n=== UPDATING EXISTING NOTIFICATIONS ===\n\n";

// Get Inventory Supervisor
$supervisor = User::role('Inventory Supervisor')->first();
if ($supervisor) {
    $notifications = $supervisor->notifications()
        ->where('type', 'App\Notifications\RequestApprovedNotification')
        ->where('read_at', null)
        ->get();
    
    echo "Found " . $notifications->count() . " unread notifications for Inventory Supervisor\n";
    
    foreach ($notifications as $notification) {
        $data = $notification->data;
        $requestId = $data['request_id'] ?? null;
        
        if ($requestId) {
            $request = \App\Models\Request::find($requestId);
            if ($request && $request->status == 'catering_approved') {
                // Update the action_url to the correct route
                $data['action_url'] = route('inventory-supervisor.requests.show', $requestId);
                
                // Update the notification
                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->update(['data' => json_encode($data)]);
                    
                echo "  ✓ Updated notification for Request #$requestId\n";
                echo "    New URL: " . $data['action_url'] . "\n";
            }
        }
    }
}

echo "\n✓ Done!\n\n";
