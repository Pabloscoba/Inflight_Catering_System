<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== 🔍 ACTIVITY LOGS DIAGNOSTIC ===\n\n";

// Check if activity_log table exists
try {
    $activities = DB::table('activity_log')->count();
    echo "✅ activity_log table exists\n";
    echo "Total activities in database: {$activities}\n\n";
} catch (Exception $e) {
    echo "❌ Error accessing activity_log table: " . $e->getMessage() . "\n\n";
    exit;
}

if ($activities > 0) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📊 ACTIVITY LOG ENTRIES (Last 10)\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    $recentActivities = DB::table('activity_log')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    foreach ($recentActivities as $activity) {
        echo "ID: {$activity->id}\n";
        echo "Log Name: {$activity->log_name}\n";
        echo "Description: {$activity->description}\n";
        echo "Causer: " . ($activity->causer_id ?? 'System') . "\n";
        echo "Subject: " . ($activity->subject_type ?? 'N/A') . " (ID: " . ($activity->subject_id ?? 'N/A') . ")\n";
        echo "Created: {$activity->created_at}\n";
        echo "Properties: " . ($activity->properties ?? 'null') . "\n";
        echo "---\n";
    }
} else {
    echo "⚠️  NO ACTIVITY LOGS FOUND!\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🔧 CREATING TEST ACTIVITY LOGS\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Get admin user
    $admin = App\Models\User::whereHas('roles', function($q) {
        $q->where('name', 'Admin');
    })->first();
    
    if ($admin) {
        echo "Using admin user: {$admin->name}\n\n";
        
        // Create sample activities
        activity('user-management')
            ->causedBy($admin)
            ->log('Admin logged in to the system');
        
        activity('system')
            ->causedBy($admin)
            ->withProperties(['action' => 'test', 'timestamp' => now()])
            ->log('System test activity created');
        
        // Permission update activity
        $role = Spatie\Permission\Models\Role::where('name', 'Catering Staff')->first();
        if ($role) {
            activity('role-management')
                ->causedBy($admin)
                ->performedOn($role)
                ->withProperties([
                    'role_name' => $role->name,
                    'permission_count' => $role->permissions->count(),
                    'permissions' => $role->permissions->pluck('name')->toArray(),
                ])
                ->log("Viewed permissions for role '{$role->name}'");
        }
        
        // Product activity
        $product = App\Models\Product::first();
        if ($product) {
            activity('product-management')
                ->causedBy($admin)
                ->performedOn($product)
                ->withProperties([
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                ])
                ->log("Viewed product '{$product->name}'");
        }
        
        // Request activity
        $request = App\Models\Request::first();
        if ($request) {
            activity('request-management')
                ->causedBy($admin)
                ->performedOn($request)
                ->withProperties([
                    'request_id' => $request->id,
                    'status' => $request->status,
                ])
                ->log("Checked request REQ-{$request->id} status");
        }
        
        echo "✅ Created 5 sample activities\n\n";
        
        $newCount = DB::table('activity_log')->count();
        echo "New total: {$newCount} activities\n\n";
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 ACTIVITY LOG BREAKDOWN BY TYPE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$logTypes = DB::table('activity_log')
    ->select('log_name', DB::raw('count(*) as count'))
    ->groupBy('log_name')
    ->orderBy('count', 'desc')
    ->get();

if ($logTypes->count() > 0) {
    foreach ($logTypes as $type) {
        echo "{$type->log_name}: {$type->count} activities\n";
    }
} else {
    echo "No activities to group\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔍 CHECKING ACTIVITY LOGGING IN ROLECONTROLLER\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Check if RoleController has activity logging
$roleControllerPath = app_path('Http/Controllers/Admin/RoleController.php');
if (file_exists($roleControllerPath)) {
    $content = file_get_contents($roleControllerPath);
    
    if (strpos($content, "activity('role-management')") !== false) {
        echo "✅ RoleController has activity logging code\n";
    } else {
        echo "⚠️  RoleController might be missing activity logging\n";
    }
    
    if (strpos($content, 'forgetCachedPermissions') !== false) {
        echo "✅ Permission cache clearing enabled\n";
    } else {
        echo "⚠️  Permission cache clearing might be missing\n";
    }
} else {
    echo "❌ RoleController not found\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🌐 CHECKING ACTIVITY LOGS ROUTE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

try {
    $url = route('admin.activity-logs.index');
    echo "✅ Activity logs route exists: {$url}\n";
} catch (Exception $e) {
    echo "❌ Activity logs route not found\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "💡 RECOMMENDATIONS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$currentCount = DB::table('activity_log')->count();

if ($currentCount == 0) {
    echo "❌ PROBLEM: No activities being logged\n\n";
    echo "Solutions:\n";
    echo "1. Check if Spatie Activity Log package is installed\n";
    echo "2. Ensure activity() helper is used in controllers\n";
    echo "3. Run: php artisan migrate (check if activity_log table exists)\n";
    echo "4. Add activity logging to key actions (login, CRUD operations)\n";
} elseif ($currentCount < 10) {
    echo "⚠️  LOW ACTIVITY: Only {$currentCount} activities logged\n\n";
    echo "This is normal for a new system. Activities will accumulate as users:\n";
    echo "- Login/logout\n";
    echo "- Create/update/delete records\n";
    echo "- Change settings\n";
    echo "- Update permissions\n";
} else {
    echo "✅ GOOD: {$currentCount} activities logged\n\n";
    echo "Activity logging is working properly!\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎯 NEXT STEPS TO POPULATE ACTIVITY LOGS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "1. Login as different users\n";
echo "2. Create/update products\n";
echo "3. Create/approve requests\n";
echo "4. Update user permissions\n";
echo "5. Change settings\n";
echo "6. Perform stock movements\n\n";

echo "Each action will create an activity log entry!\n";
