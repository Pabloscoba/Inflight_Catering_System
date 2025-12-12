<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking and Fixing Request Statuses ===\n\n";

// Get all requests with their current statuses
$requests = DB::table('requests')->select('id', 'status')->get();

echo "Total requests: " . $requests->count() . "\n\n";

$validStatuses = [
    'pending',
    'pending_inventory',
    'pending_supervisor',
    'supervisor_approved',
    'sent_to_security',
    'security_approved',
    'catering_approved',
    'approved',
    'rejected',
    'received'
];

$invalidRequests = [];

foreach ($requests as $req) {
    if (!in_array($req->status, $validStatuses)) {
        $invalidRequests[] = $req;
        echo "❌ Request #{$req->id} has INVALID status: '{$req->status}'\n";
    } else {
        echo "✅ Request #{$req->id}: {$req->status}\n";
    }
}

if (count($invalidRequests) > 0) {
    echo "\n\n=== Fixing Invalid Statuses ===\n\n";
    
    foreach ($invalidRequests as $req) {
        // Try to map invalid statuses to valid ones
        $newStatus = 'pending_inventory'; // default
        
        if (str_contains($req->status, 'dispatch')) {
            $newStatus = 'catering_approved'; // temporary - will be updated after migration
        } elseif (str_contains($req->status, 'ramp')) {
            $newStatus = 'catering_approved';
        }
        
        DB::table('requests')->where('id', $req->id)->update(['status' => $newStatus]);
        echo "✅ Fixed Request #{$req->id}: '{$req->status}' → '{$newStatus}'\n";
    }
    
    echo "\n✅ All invalid statuses fixed! You can now run 'php artisan migrate'\n";
} else {
    echo "\n✅ All request statuses are valid!\n";
}
