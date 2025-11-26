<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Checking Requests #6 and #7 ===\n\n";

$requests = App\Models\Request::with('flight')->whereIn('id', [6, 7])->get();

foreach ($requests as $req) {
    echo "Request #{$req->id}:\n";
    echo "  Status: {$req->status}\n";
    echo "  Flight: {$req->flight->flight_number}\n";
    echo "  Departure: {$req->flight->departure_time}\n";
    echo "  Departure is future? " . ($req->flight->departure_time > now() ? 'YES' : 'NO') . "\n";
    echo "\n";
}

echo "=== All ready_for_dispatch requests ===\n";
$allReady = App\Models\Request::where('status', 'ready_for_dispatch')->get(['id', 'status', 'flight_id']);
echo "Count: " . $allReady->count() . "\n";
foreach ($allReady as $r) {
    echo "  Request #{$r->id}: flight_id={$r->flight_id}\n";
}

echo "\n=== Requests matching dashboard query ===\n";
$ordersToDispatch = App\Models\Request::with(['flight', 'requester'])
    ->where('status', 'ready_for_dispatch')
    ->whereHas('flight', function($query) {
        $query->where('departure_time', '>', now());
    })
    ->get();
echo "Count: " . $ordersToDispatch->count() . "\n";
foreach ($ordersToDispatch as $order) {
    echo "  Request #{$order->id}: Flight {$order->flight->flight_number}, Departure: {$order->flight->departure_time}\n";
}
