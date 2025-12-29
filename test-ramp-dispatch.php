<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Ramp Dispatcher Dispatch ===" . PHP_EOL . PHP_EOL;

// Find Request #3
$request = App\Models\Request::find(3);

if (!$request) {
    echo "Request #3 not found!" . PHP_EOL;
    exit(1);
}

echo "Before:" . PHP_EOL;
echo "Status: " . $request->status . PHP_EOL;
echo "Dispatched By: " . ($request->dispatched_by ?? 'NULL') . PHP_EOL;
echo "Dispatched At: " . ($request->dispatched_at ?? 'NULL') . PHP_EOL;

if ($request->status !== 'security_authenticated') {
    echo PHP_EOL . "ERROR: Request is not in 'security_authenticated' status!" . PHP_EOL;
    exit(1);
}

// Find Ramp Dispatcher user
$rampDispatcher = App\Models\User::role('Ramp Dispatcher')->first();
if (!$rampDispatcher) {
    echo "ERROR: No Ramp Dispatcher user found!" . PHP_EOL;
    exit(1);
}

// Update request
$request->update([
    'status' => 'ramp_dispatched',
    'dispatched_by' => $rampDispatcher->id,
    'dispatched_at' => now(),
]);

echo PHP_EOL . "After:" . PHP_EOL;
echo "Status: " . $request->status . PHP_EOL;
echo "Dispatched By: " . $rampDispatcher->name . PHP_EOL;
echo "Dispatched At: " . $request->dispatched_at . PHP_EOL;

// Notify Flight Purser
$flightPursers = App\Models\User::role('Flight Purser')->get();
echo PHP_EOL . "Flight Purser users found: " . $flightPursers->count() . PHP_EOL;
foreach ($flightPursers as $purser) {
    $purser->notify(new \App\Notifications\RequestApprovedNotification($request));
    echo "✓ Notification sent to Flight Purser: " . $purser->name . PHP_EOL;
}

echo PHP_EOL . "SUCCESS! Request #3 dispatched to Flight Purser." . PHP_EOL;
