<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Request as RequestModel;

echo "=== Checking Recent Authenticated Requests ===\n\n";

// This is the exact query from CateringIncharge/DashboardController
$recentAuthenticatedRequests = RequestModel::with(['flight', 'requester', 'items.product'])
    ->whereIn('status', ['security_authenticated', 'ramp_dispatched', 'loaded', 'delivered'])
    ->latest('updated_at')
    ->limit(10)
    ->get();

echo "Query: whereIn('status', ['security_authenticated', 'ramp_dispatched', 'loaded', 'delivered'])\n";
echo "Found: {$recentAuthenticatedRequests->count()} requests\n\n";

if ($recentAuthenticatedRequests->count() > 0) {
    echo "Requests that should appear on Catering Incharge dashboard:\n";
    foreach ($recentAuthenticatedRequests as $req) {
        echo "  Request #{$req->id}\n";
        echo "    Flight: {$req->flight->flight_number}\n";
        echo "    Status: {$req->status}\n";
        echo "    Requester: {$req->requester->name}\n";
        echo "    Items: {$req->items->count()}\n";
        echo "    Updated: {$req->updated_at->format('Y-m-d H:i:s')}\n";
        echo "\n";
    }
} else {
    echo "❌ No requests found with statuses: security_authenticated, ramp_dispatched, loaded, or delivered\n";
    echo "\nAll requests in database:\n";
    $all = RequestModel::with('flight')->get();
    foreach ($all as $r) {
        $flight = $r->flight->flight_number ?? 'N/A';
        echo "  Request #{$r->id} - Status: {$r->status} - Flight: {$flight}\n";
    }
}
