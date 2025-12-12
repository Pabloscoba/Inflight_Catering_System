<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== COMPLETE WORKFLOW STATUS CHECK ===\n\n";

// Check Request Status
$request = App\Models\Request::with(['flight', 'requester', 'items.product'])->first();

if (!$request) {
    echo "❌ NO REQUESTS IN DATABASE\n";
    exit;
}

echo "📋 REQUEST DETAILS:\n";
echo "  ID: #{$request->id}\n";
echo "  Flight: {$request->flight->flight_number}\n";
echo "  Requester: {$request->requester->name} ({$request->requester->roles->first()->name})\n";
echo "  Current Status: **{$request->status}**\n\n";

echo "🔄 WORKFLOW PROGRESS:\n";
$steps = [
    ['status' => 'pending_inventory', 'label' => '1️⃣ Catering Staff creates', 'actor' => 'Catering Staff', 'done' => true],
    ['status' => 'pending_supervisor', 'label' => '2️⃣ Inventory Personnel forwards to Supervisor', 'actor' => 'Inventory Personnel', 'done' => $request->forwarded_to_supervisor_at !== null],
    ['status' => 'supervisor_approved', 'label' => '3️⃣ Inventory Supervisor approves', 'actor' => 'Inventory Supervisor', 'done' => $request->supervisor_approved_at !== null],
    ['status' => 'sent_to_security', 'label' => '4️⃣ Inventory Personnel forwards to Security', 'actor' => 'Inventory Personnel', 'done' => $request->forwarded_to_security_at !== null],
    ['status' => 'security_approved', 'label' => '5️⃣ Security authenticates', 'actor' => 'Security Staff', 'done' => $request->security_authenticated_at !== null],
    ['status' => 'catering_approved', 'label' => '6️⃣ Catering Incharge approves', 'actor' => 'Catering Incharge', 'done' => $request->catering_approved_at !== null],
    ['status' => 'sent_to_ramp', 'label' => '7️⃣ Catering Staff sends to Ramp', 'actor' => 'Catering Staff', 'done' => $request->sent_to_ramp_at !== null],
];

foreach ($steps as $step) {
    $icon = $step['done'] ? '✅' : '⏸️';
    $current = ($request->status === $step['status']) ? ' ⬅️ CURRENT' : '';
    echo "{$icon} {$step['label']}{$current}\n";
}

echo "\n🎯 NEXT ACTION REQUIRED:\n";
if ($request->status === 'supervisor_approved') {
    echo "  👤 WHO: Inventory Personnel\n";
    echo "  📍 WHERE: Go to Dashboard → Click 'Forward to Security' button\n";
    echo "  🔗 OR: Visit 'Supervisor Approved Requests' page\n";
    echo "  ✅ WHAT: Click 'Forward to Security' button for Request #{$request->id}\n";
    echo "  📊 THEN: Status will change to 'sent_to_security'\n";
    echo "  🔒 RESULT: Security Staff will see request on their dashboard\n";
} elseif ($request->status === 'sent_to_security') {
    echo "  👤 WHO: Security Staff\n";
    echo "  📍 WHERE: Security Dashboard → Orders Pending Security Check\n";
    echo "  ✅ WHAT: Authenticate Request #{$request->id}\n";
} else {
    echo "  Status: {$request->status}\n";
}

echo "\n🔍 DATABASE CHECKS:\n";
echo "  Request exists: ✅\n";
echo "  Flight exists: " . ($request->flight ? '✅' : '❌') . "\n";
echo "  Items count: " . $request->items->count() . " items\n";

echo "\n📊 SECURITY DASHBOARD QUERY:\n";
$securityRequests = App\Models\Request::where('status', 'sent_to_security')->count();
echo "  Requests with 'sent_to_security' status: {$securityRequests}\n";
if ($securityRequests === 0) {
    echo "  ⚠️ This is why Security sees '0 pending'\n";
    echo "  ℹ️ Request must be forwarded first by Inventory Personnel\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
