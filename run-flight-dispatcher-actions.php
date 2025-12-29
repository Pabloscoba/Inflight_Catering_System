<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Request as RequestModel;
use App\Models\User;

$request = RequestModel::find(10);
if (! $request) {
    echo "Request #10 not found\n";
    exit(1);
}

$dispatcher = User::where('email', 'flight.dispatcher@inflightcatering.com')->first();
if (! $dispatcher) {
    echo "Flight Dispatcher user not found\n";
    exit(1);
}

// Add comment and recommend
$request->update([
    'dispatcher_comments' => 'Checked by automated test: looks good.',
    'dispatcher_recommended' => true,
    'dispatcher_recommended_by' => $dispatcher->id,
    'dispatcher_recommended_at' => now(),
]);

echo "Updated Request #{$request->id}: dispatcher_recommended=" . ($request->dispatcher_recommended ? 'true' : 'false') . "\n";

echo "Notifying Flight Purser(s)...\n";
$flightPursers = User::role('Flight Purser')->get();
foreach ($flightPursers as $purser) {
    $purser->notify(new \App\Notifications\RequestApprovedNotification($request));
    echo " - Notification sent to: {$purser->email}\n";
}

echo "Done.\n";
