<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Flight;
use App\Models\FlightDispatch;
use App\Models\Request as RequestModel;
use App\Models\RequestMessage;

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔍 FLIGHT DISPATCHER DATA CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Check Flights
$flightsCount = Flight::count();
echo "✈️  Flights in database: {$flightsCount}\n";

if ($flightsCount > 0) {
    $todaysFlights = Flight::whereDate('departure_time', today())->count();
    echo "   └─ Today's flights: {$todaysFlights}\n";
}

// Check Dispatch Records
$dispatchesCount = FlightDispatch::count();
echo "\n📋 Dispatch records: {$dispatchesCount}\n";

if ($dispatchesCount > 0) {
    echo "\n   Recent Dispatches:\n";
    $recentDispatches = FlightDispatch::with(['flight'])->latest()->take(5)->get();
    foreach ($recentDispatches as $dispatch) {
        $flightNumber = $dispatch->flight->flight_number ?? 'N/A';
        echo "   - Dispatch #{$dispatch->id} for Flight {$flightNumber} ({$dispatch->overall_status})\n";
    }
}

// Check Requests
$requestsCount = RequestModel::count();
echo "\n📝 Requests in database: {$requestsCount}\n";

if ($requestsCount > 0) {
    $awaitingDispatcher = RequestModel::whereIn('status', ['awaiting_flight_dispatcher', 'ramp_dispatched'])->count();
    echo "   └─ Awaiting Flight Dispatcher: {$awaitingDispatcher}\n";
}

// Check Messages
$messagesCount = RequestMessage::forRole('Flight Dispatcher')->count();
$unreadCount = RequestMessage::forRole('Flight Dispatcher')->unread()->count();
echo "\n💬 Messages for Flight Dispatcher: {$messagesCount}\n";
echo "   └─ Unread: {$unreadCount}\n";

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if ($flightsCount === 0) {
    echo "\n⚠️  WARNING: No flights in database!\n";
    echo "   You need to create flights before using the dashboard.\n";
    echo "   Go to: http://127.0.0.1:8000/admin/flights/create\n";
}

if ($dispatchesCount === 0) {
    echo "\n💡 TIP: No dispatch records yet.\n";
    echo "   Create one at: http://127.0.0.1:8000/flight-dispatcher/dispatches/create\n";
}

echo "\n";
