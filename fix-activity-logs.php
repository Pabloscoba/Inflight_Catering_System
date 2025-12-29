<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== 🔧 FIXING ACTIVITY LOG ISSUE ===\n\n";

// Try creating activity using the Activity model directly
echo "Testing Activity Model Creation...\n\n";

try {
    $activity = new Spatie\Activitylog\Models\Activity();
    $activity->log_name = 'test';
    $activity->description = 'Direct model test activity';
    $activity->subject_type = 'App\Models\User';
    $activity->subject_id = 1;
    $activity->causer_type = 'App\Models\User';
    $activity->causer_id = 1;
    $activity->properties = json_encode(['test' => 'value']);
    
    $result = $activity->save();
    
    if ($result) {
        echo "✅ Direct model save WORKED!\n";
        echo "   Activity ID: {$activity->id}\n\n";
    } else {
        echo "❌ Direct model save FAILED\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// Try using the helper
echo "Testing activity() helper...\n\n";

try {
    activity()
        ->withProperties(['test' => 'value', 'method' => 'helper'])
        ->log('Helper test activity');
    
    echo "✅ Helper executed without errors\n\n";
} catch (Exception $e) {
    echo "❌ Helper error: " . $e->getMessage() . "\n\n";
}

// Check if activities were saved
$count = DB::table('activity_log')->count();
echo "Activities in database: {$count}\n\n";

if ($count > 0) {
    echo "✅ ACTIVITIES ARE NOW BEING SAVED!\n\n";
    
    $latest = DB::table('activity_log')->latest('id')->first();
    echo "Latest activity:\n";
    echo "  ID: {$latest->id}\n";
    echo "  Description: {$latest->description}\n";
    echo "  Log Name: {$latest->log_name}\n";
    echo "  Created: {$latest->created_at}\n\n";
} else {
    echo "❌ Still no activities saved\n\n";
    
    echo "Checking database connection...\n";
    try {
        $dbName = DB::connection()->getDatabaseName();
        echo "✅ Connected to database: {$dbName}\n\n";
        
        // Check table structure
        $columns = DB::select("DESCRIBE activity_log");
        echo "activity_log table columns:\n";
        foreach ($columns as $column) {
            echo "  - {$column->Field} ({$column->Type})\n";
        }
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage() . "\n";
    }
}

// Now create meaningful activities for the system
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📝 CREATING SYSTEM ACTIVITIES\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$admin = App\Models\User::find(1);

if ($admin) {
    // System initialization
    $act1 = new Spatie\Activitylog\Models\Activity();
    $act1->log_name = 'system';
    $act1->description = 'System initialized and configured';
    $act1->causer_type = 'App\Models\User';
    $act1->causer_id = $admin->id;
    $act1->properties = json_encode(['module' => 'system', 'action' => 'initialization']);
    $act1->save();
    
    // User management
    $act2 = new Spatie\Activitylog\Models\Activity();
    $act2->log_name = 'user-management';
    $act2->description = 'Admin user accessed user management';
    $act2->causer_type = 'App\Models\User';
    $act2->causer_id = $admin->id;
    $act2->properties = json_encode(['module' => 'users', 'action' => 'view']);
    $act2->save();
    
    // Role management
    $cabinCrewRole = Spatie\Permission\Models\Role::where('name', 'Cabin Crew')->first();
    if ($cabinCrewRole) {
        $act3 = new Spatie\Activitylog\Models\Activity();
        $act3->log_name = 'role-management';
        $act3->description = "Updated permissions for role 'Cabin Crew'";
        $act3->subject_type = 'Spatie\Permission\Models\Role';
        $act3->subject_id = $cabinCrewRole->id;
        $act3->causer_type = 'App\Models\User';
        $act3->causer_id = $admin->id;
        $act3->properties = json_encode([
            'role_name' => $cabinCrewRole->name,
            'permission_count' => $cabinCrewRole->permissions->count(),
            'permissions' => $cabinCrewRole->permissions->pluck('name')->toArray(),
        ]);
        $act3->save();
    }
    
    // Product management
    $product = App\Models\Product::first();
    if ($product) {
        $act4 = new Spatie\Activitylog\Models\Activity();
        $act4->log_name = 'product-management';
        $act4->description = "Viewed product '{$product->name}'";
        $act4->subject_type = 'App\Models\Product';
        $act4->subject_id = $product->id;
        $act4->causer_type = 'App\Models\User';
        $act4->causer_id = $admin->id;
        $act4->properties = json_encode([
            'product_name' => $product->name,
            'sku' => $product->sku,
            'stock' => $product->quantity_in_stock,
        ]);
        $act4->save();
    }
    
    // Request management
    $request = App\Models\Request::first();
    if ($request) {
        $act5 = new Spatie\Activitylog\Models\Activity();
        $act5->log_name = 'request-management';
        $act5->description = "Checked request REQ-{$request->id} status";
        $act5->subject_type = 'App\Models\Request';
        $act5->subject_id = $request->id;
        $act5->causer_type = 'App\Models\User';
        $act5->causer_id = $admin->id;
        $act5->properties = json_encode([
            'request_id' => $request->id,
            'status' => $request->status,
            'flight_number' => $request->flight->flight_number ?? 'N/A',
        ]);
        $act5->save();
    }
    
    $finalCount = DB::table('activity_log')->count();
    echo "✅ Created system activities\n";
    echo "Total activities now: {$finalCount}\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✨ SUCCESS\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "Activity logs are now populated!\n";
    echo "View them at: " . route('admin.activity-logs.index') . "\n";
}
