<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Find catering staff user
$cateringStaff = App\Models\User::role('Catering Staff')->first();

if (!$cateringStaff) {
    echo "No Catering Staff user found!\n";
    exit;
}

echo "Catering Staff User: {$cateringStaff->name} (ID: {$cateringStaff->id})\n";
echo "Email: {$cateringStaff->email}\n\n";

// Get all requests by this user
$requests = App\Models\Request::where('requester_id', $cateringStaff->id)->get();

echo "Total Requests: " . $requests->count() . "\n";
echo "==================\n\n";

if ($requests->count() > 0) {
    foreach ($requests as $request) {
        echo "Request #{$request->id}\n";
        echo "  Status: {$request->status}\n";
        echo "  Flight: {$request->flight->flight_number}\n";
        echo "  Created: {$request->created_at}\n\n";
    }
    
    echo "\nBreakdown by Status:\n";
    echo "--------------------\n";
    $statusCounts = $requests->groupBy('status');
    foreach ($statusCounts as $status => $reqs) {
        echo "$status: " . $reqs->count() . "\n";
    }
} else {
    echo "No requests found for this user.\n";
}
