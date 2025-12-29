<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Dispatch Request #10 via Ramp Dispatcher (to Flight Dispatcher) ===" . PHP_EOL . PHP_EOL;

$request = App\Models\Request::find(10);
if (!$request) {
    echo "Request #10 not found!" . PHP_EOL;
    exit(1);
}

echo "Before status: " . $request->status . PHP_EOL;

if ($request->status !== 'security_authenticated') {
    echo "ERROR: Request #10 is not in 'security_authenticated' status!" . PHP_EOL;
    exit(1);
}

$ramp = App\Models\User::role('Ramp Dispatcher')->first();
if (!$ramp) {
    echo "ERROR: No Ramp Dispatcher user found!" . PHP_EOL;
    exit(1);
}

<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

echo "=== Dispatch Request #10 via Ramp Dispatcher (to Flight Dispatcher) ===" . PHP_EOL . PHP_EOL;

$request = App\Models\Request::find(10);
if (!$request) {
    echo "Request #10 not found!" . PHP_EOL;
    exit(1);
}

echo "Before status: " . $request->status . PHP_EOL;

if ($request->status !== 'security_authenticated') {
    echo "ERROR: Request #10 is not in 'security_authenticated' status!" . PHP_EOL;
    exit(1);
}

$ramp = App\Models\User::role('Ramp Dispatcher')->first();
if (!$ramp) {
    echo "ERROR: No Ramp Dispatcher user found!" . PHP_EOL;
    exit(1);
}

$request->update([
    'status' => 'awaiting_flight_dispatcher',
    'dispatched_by' => $ramp->id,
    'dispatched_at' => now(),
]);

echo "After status: " . $request->status . PHP_EOL;
echo "Dispatched By: " . $ramp->name . PHP_EOL;

$flightDispatchers = App\Models\User::role('Flight Dispatcher')->get();
echo PHP_EOL . "Flight Dispatcher users found: " . $flightDispatchers->count() . PHP_EOL;
foreach ($flightDispatchers as $fd) {
    $fd->notify(new \App\Notifications\RequestApprovedNotification($request));
    echo "✓ Notification sent to Flight Dispatcher: " . $fd->name . PHP_EOL;
}

echo PHP_EOL . "SUCCESS! Request #10 sent to Flight Dispatcher." . PHP_EOL;
