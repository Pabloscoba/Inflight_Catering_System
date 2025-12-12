<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Request as RequestModel;
use App\Models\Product;
use App\Models\Flight;
use App\Models\RequestItem;
use App\Notifications\NewRequestNotification;
use App\Notifications\RequestApprovedNotification;

echo str_repeat("=", 80) . "\n";
echo "           TESTING NOTIFICATIONS WITH REAL WORKFLOW\n";
echo str_repeat("=", 80) . "\n\n";

// Get users
$cateringStaff = User::role('Catering Staff')->first();
$cateringIncharge = User::role('Catering Incharge')->first();
$securityStaff = User::role('Security Staff')->first();

if (!$cateringStaff || !$cateringIncharge || !$securityStaff) {
    echo "❌ Required users not found\n";
    exit(1);
}

// 1. Test New Request Notification
echo "1️⃣ TESTING NEW REQUEST NOTIFICATION:\n";
echo str_repeat("-", 80) . "\n";

$flight = Flight::first();
$product = Product::where('status', 'approved')->first();

if (!$flight || !$product) {
    echo "❌ Flight or Product not found\n";
    exit(1);
}

// Simulate Catering Staff creating a request
$request = RequestModel::create([
    'flight_id' => $flight->id,
    'requester_id' => $cateringStaff->id,
    'requested_date' => now()->addDays(2)->toDateString(),
    'notes' => 'Test request for notification system',
    'request_type' => 'product',
    'status' => 'pending_inventory',
]);

RequestItem::create([
    'request_id' => $request->id,
    'product_id' => $product->id,
    'quantity_requested' => 50,
]);

// Send notification to Catering Incharge
$cateringIncharge->notify(new NewRequestNotification($request));

echo "✓ Request #{$request->id} created by {$cateringStaff->name}\n";
echo "✓ Notification sent to {$cateringIncharge->name}\n";
echo "  Notification count for Catering Incharge: " . $cateringIncharge->fresh()->notifications->count() . "\n";
echo "  Unread count: " . $cateringIncharge->fresh()->unreadNotifications->count() . "\n\n";

// 2. Test Request Approved Notification
echo "2️⃣ TESTING REQUEST APPROVED NOTIFICATION:\n";
echo str_repeat("-", 80) . "\n";

$request->update([
    'status' => 'supervisor_approved',
    'approved_by' => $cateringIncharge->id,
    'approved_date' => now(),
]);

// Send approval notification to requester
$cateringStaff->notify(new RequestApprovedNotification($request));

// Send to Security Staff
$securityStaff->notify(new RequestApprovedNotification($request));

echo "✓ Request approved by {$cateringIncharge->name}\n";
echo "✓ Notification sent to {$cateringStaff->name} (requester)\n";
echo "✓ Notification sent to {$securityStaff->name}\n";
echo "  Notification count for Catering Staff: " . $cateringStaff->fresh()->notifications->count() . "\n";
echo "  Notification count for Security Staff: " . $securityStaff->fresh()->notifications->count() . "\n\n";

// 3. Check all notifications
echo "3️⃣ ALL NOTIFICATIONS IN SYSTEM:\n";
echo str_repeat("-", 80) . "\n";

$allNotifications = DB::table('notifications')->get();
foreach ($allNotifications as $notification) {
    $data = json_decode($notification->data, true);
    $user = User::find($notification->notifiable_id);
    $readStatus = $notification->read_at ? '📖 Read' : '🔔 Unread';
    
    echo "{$readStatus} | {$user->name} | {$data['title']}\n";
    echo "      └─ {$data['message']}\n\n";
}

// 4. Test Mark as Read
echo "4️⃣ TESTING MARK AS READ:\n";
echo str_repeat("-", 80) . "\n";

$unreadCount = $cateringIncharge->fresh()->unreadNotifications->count();
echo "Unread notifications for {$cateringIncharge->name}: {$unreadCount}\n";

$cateringIncharge->unreadNotifications->first()?->markAsRead();

$newUnreadCount = $cateringIncharge->fresh()->unreadNotifications->count();
echo "After marking one as read: {$newUnreadCount}\n\n";

// CLEANUP
echo "5️⃣ CLEANUP:\n";
echo str_repeat("-", 80) . "\n";
echo "Do you want to delete test data? (keeping for testing)\n";
echo "Test request ID: {$request->id}\n";
echo "You can delete manually if needed.\n\n";

// FINAL STATS
echo str_repeat("=", 80) . "\n";
echo "FINAL STATISTICS:\n";
echo str_repeat("=", 80) . "\n\n";

$totalNotifications = DB::table('notifications')->count();
$unreadTotal = DB::table('notifications')->whereNull('read_at')->count();

echo "📊 Total Notifications: {$totalNotifications}\n";
echo "🔔 Unread: {$unreadTotal}\n";
echo "📖 Read: " . ($totalNotifications - $unreadTotal) . "\n\n";

echo "🎯 Notifications by User:\n";
foreach (User::has('notifications')->get() as $user) {
    $count = $user->notifications->count();
    $unread = $user->unreadNotifications->count();
    echo "  • {$user->name}: {$count} total ({$unread} unread)\n";
}

echo "\n✅ NOTIFICATION SYSTEM TEST COMPLETED!\n";
echo str_repeat("=", 80) . "\n";
