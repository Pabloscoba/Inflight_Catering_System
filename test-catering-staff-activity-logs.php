<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Activitylog\Models\Activity;

echo "═══════════════════════════════════════════════════\n";
echo "🧪 TESTING ACTIVITY LOGS FOR CATERING STAFF\n";
echo "═══════════════════════════════════════════════════\n\n";

// Find Catering Staff user
$cateringStaff = User::whereHas('roles', function($q) {
    $q->where('name', 'Catering Staff');
})->first();

if (!$cateringStaff) {
    echo "❌ No Catering Staff user found!\n";
    exit;
}

echo "User: {$cateringStaff->name}\n";
echo "Role: " . $cateringStaff->roles->pluck('name')->join(', ') . "\n\n";

// Check if has permission
$hasPermission = $cateringStaff->can('view activity logs');
echo "Has 'view activity logs' permission? " . ($hasPermission ? '✅ YES' : '❌ NO') . "\n\n";

if (!$hasPermission) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "❌ PERMISSION NOT GRANTED\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "Go to: http://127.0.0.1:8000/admin/roles\n";
    echo "1. Click 'Edit' on Catering Staff role\n";
    echo "2. Check 'view activity logs' permission\n";
    echo "3. Click 'Update Permissions'\n";
    echo "4. Log out and log back in as Catering Staff\n\n";
    exit;
}

// Count activities
$totalActivities = Activity::count();
$todayActivities = Activity::whereDate('created_at', today())->count();

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 ACTIVITY LOGS STATISTICS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Total activities: {$totalActivities}\n";
echo "Today's activities: {$todayActivities}\n\n";

if ($totalActivities > 0) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📋 LATEST 10 ACTIVITIES\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    $latestActivities = Activity::with('causer')
        ->latest()
        ->take(10)
        ->get();
    
    foreach ($latestActivities as $index => $activity) {
        $num = $index + 1;
        $time = $activity->created_at->diffForHumans();
        $user = $activity->causer ? $activity->causer->name : 'System';
        
        echo "{$num}. {$activity->description}\n";
        echo "   👤 {$user} | 🕐 {$time} | 📁 {$activity->log_name}\n\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ SUCCESS! Activity logs are working!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "🌐 VIEW IN BROWSER:\n";
    echo "URL: " . route('admin.activity-logs.index') . "\n\n";
    
    echo "📱 ACCESS AS CATERING STAFF:\n";
    echo "1. Log out from admin account\n";
    echo "2. Log in as Catering Staff\n";
    echo "3. Click 'Activity Logs' button on dashboard\n";
    echo "4. You should see all {$totalActivities} activities\n\n";
    
} else {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "⚠️  NO ACTIVITIES FOUND\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "Activities are now being logged automatically:\n";
    echo "✓ When you create/update/delete users\n";
    echo "✓ When you create/update/delete products\n";
    echo "✓ When you create/update requests\n\n";
    echo "Try performing some actions and they will appear here!\n\n";
}

echo "🎯 FEATURES:\n";
echo "✓ Permission-based access (any role with permission can view)\n";
echo "✓ Automatic logging via Model Observers\n";
echo "✓ Filter by user, date, event type, log name\n";
echo "✓ Export to CSV\n";
echo "✓ Delete old logs\n";
echo "✓ Detailed view for each activity\n\n";
