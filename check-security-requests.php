<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Request as RequestModel;

echo "=== Security Staff Dashboard Data Check ===\n\n";

// Count requests at catering_final_approved status
$pendingCount = RequestModel::where('status', 'catering_final_approved')->count();
echo "Requests pending security verification: {$pendingCount}\n\n";

// Show all requests with their statuses
$allRequests = RequestModel::with('flight')->latest()->limit(10)->get();
echo "Recent requests:\n";
foreach ($allRequests as $req) {
    $flightNumber = $req->flight->flight_number ?? 'N/A';
    echo "  Request #{$req->id} - Status: {$req->status} - Flight: {$flightNumber}\n";
}

echo "\n";

// If there are pending requests, show details
if ($pendingCount > 0) {
    echo "Requests awaiting authentication:\n";
    $pending = RequestModel::with(['flight', 'requester', 'items'])->where('status', 'catering_final_approved')->get();
    foreach ($pending as $req) {
        echo "  Request #{$req->id}\n";
        echo "    Flight: {$req->flight->flight_number}\n";
        echo "    Requester: {$req->requester->name}\n";
        echo "    Items: {$req->items->count()}\n";
        echo "    Departure: {$req->flight->departure_time}\n";
        echo "\n";
    }
} else {
    echo "No requests at catering_final_approved status.\n";
    echo "You may need to create a test request or move an existing request to this status.\n";
}
