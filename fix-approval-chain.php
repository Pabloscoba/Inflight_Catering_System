<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Updating Request #1 Approval Fields ===\n\n";

$request = App\Models\Request::find(1);

// Get users for workflow
$inventorySupervisor = App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'Inventory Supervisor'))->first();
$securityStaff = App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'Security Staff'))->first();
$cateringIncharge = App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'Catering Incharge'))->first();
$rampDispatcher = App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'Ramp Dispatcher'))->first();
$flightPurser = App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'Flight Purser'))->first();

echo "Updating approval fields with proper user IDs...\n\n";

$request->update([
    'approved_by' => $inventorySupervisor->id ?? 7,
    'approved_at' => now()->subDays(4)->setTime(10, 30, 0),
    'security_dispatched_by' => $securityStaff->id ?? 4,
    'security_dispatched_at' => now()->subDays(4)->setTime(10, 37, 0),
    'catering_approved_by' => $cateringIncharge->id ?? 5,
    'catering_approved_at' => now()->subDays(4)->setTime(10, 40, 0),
    'loaded_by' => $flightPurser->id ?? 9,
    'loaded_at' => now()->subDays(4)->setTime(13, 45, 0),
]);

echo "✅ Updated Request #1 with complete approval chain:\n";
$request->refresh();
echo "  Supervisor Approved By: User #{$request->approved_by} at {$request->approved_at}\n";
echo "  Security Authenticated By: User #{$request->security_dispatched_by} at {$request->security_dispatched_at}\n";
echo "  Catering Approved By: User #{$request->catering_approved_by} at {$request->catering_approved_at}\n";
echo "  Dispatched By: User #{$request->dispatched_by} at {$request->dispatched_at}\n";
echo "  Loaded By: User #{$request->loaded_by} at {$request->loaded_at}\n";

echo "\n✅ Request #1 now has complete approval trail!\n";
