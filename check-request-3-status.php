<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Request #3 Current Status ===" . PHP_EOL . PHP_EOL;

$request = App\Models\Request::with(['flight', 'requester', 'approver'])->find(3);

if ($request) {
    echo "Request ID: " . $request->id . PHP_EOL;
    echo "Flight: " . $request->flight->flight_number . PHP_EOL;
    echo "Requester: " . $request->requester->name . PHP_EOL;
    echo "Current Status: " . $request->status . PHP_EOL;
    echo "Created: " . $request->created_at . PHP_EOL;
    
    if ($request->approver) {
        echo "Approved By: " . $request->approver->name . PHP_EOL;
        echo "Approved At: " . $request->approved_at . PHP_EOL;
    }
    
    if ($request->received_by) {
        $receiver = App\Models\User::find($request->received_by);
        echo "Received By: " . ($receiver ? $receiver->name : 'Unknown') . PHP_EOL;
        echo "Received At: " . $request->received_date . PHP_EOL;
    }
    
    echo PHP_EOL . "=== Request Items ===" . PHP_EOL;
    foreach ($request->items as $item) {
        echo "- " . $item->product->name . " (Qty: " . $item->quantity . ")" . PHP_EOL;
    }
} else {
    echo "Request #3 not found!" . PHP_EOL;
}

echo PHP_EOL . "=== All Items Issued Requests ===" . PHP_EOL;
$issuedRequests = App\Models\Request::where('status', 'items_issued')->with(['flight', 'requester'])->get();
echo "Count: " . $issuedRequests->count() . PHP_EOL;
foreach ($issuedRequests as $req) {
    echo "Request #" . $req->id . " - Flight: " . $req->flight->flight_number . " - Requester: " . $req->requester->name . PHP_EOL;
}
