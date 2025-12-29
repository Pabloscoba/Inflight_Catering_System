<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Request;
use App\Models\RequestItem;
use App\Models\StockMovement;
use App\Models\Activity;

echo "=== RESET REQUESTS - DELETE ALL REQUESTS (AUTO) ===\n\n";

try {
    DB::beginTransaction();
    
    // Get counts before deletion
    $requestCount = Request::count();
    $requestItemCount = RequestItem::count();
    
    echo "📊 Current Data:\n";
    echo "  - Requests: {$requestCount}\n";
    echo "  - Request Items: {$requestItemCount}\n\n";
    
    if ($requestCount == 0) {
        echo "ℹ️  No requests to delete. Database is already clean.\n";
        DB::commit();
        exit(0);
    }
    
    echo "🔄 Starting deletion process...\n\n";
    
    // Delete in correct order (respect foreign key constraints)
    
    // 1. Delete additional product requests first
    echo "🗑️  Deleting additional product requests...\n";
    $deleted = DB::table('additional_product_requests')->delete();
    echo "   ✅ Deleted {$deleted} additional product requests\n\n";
    
    // 2. Delete request items
    echo "🗑️  Deleting request items...\n";
    $deleted = DB::table('request_items')->delete();
    echo "   ✅ Deleted {$deleted} request items\n\n";
    
    // 3. Delete activity logs related to requests
    echo "🗑️  Deleting activity logs...\n";
    $deleted = DB::table('activity_log')
        ->where('subject_type', 'App\\Models\\Request')
        ->delete();
    echo "   ✅ Deleted {$deleted} activity logs\n\n";
    
    // 4. Delete notifications related to requests
    echo "🗑️  Deleting notifications related to requests...\n";
    $deleted = DB::table('notifications')
        ->where('type', 'LIKE', '%Request%')
        ->delete();
    echo "   ✅ Deleted {$deleted} notifications\n\n";
    
    // 5. Finally delete requests using delete() instead of truncate()
    echo "🗑️  Deleting all requests...\n";
    $deleted = DB::table('requests')->delete();
    echo "   ✅ Deleted {$deleted} requests\n\n";
    
    DB::commit();
    
    echo "✅ SUCCESS! All requests and related data have been deleted.\n\n";
    
    // Verify deletion
    echo "📊 Verification:\n";
    echo "  - Requests remaining: " . Request::count() . "\n";
    echo "  - Request Items remaining: " . RequestItem::count() . "\n\n";
    
    echo "✨ Database is clean! You can now start fresh with the new workflow!\n\n";
    
    echo "🔄 NEW WORKFLOW (9 Steps):\n";
    echo "══════════════════════════════════════════════════════════════════\n";
    echo "Step 1: Catering Staff creates request → pending_catering_incharge\n";
    echo "Step 2: Catering Incharge approves → catering_approved\n";
    echo "Step 3: Inventory Supervisor approves → supervisor_approved\n";
    echo "Step 4: Inventory Personnel issues items → items_issued\n";
    echo "Step 5: Catering Staff receives items → pending_final_approval\n";
    echo "Step 6: Catering Incharge final approval → catering_final_approved\n";
    echo "Step 7: Security authenticates → security_authenticated\n";
    echo "Step 8: Ramp Dispatcher dispatches → ramp_dispatched\n";
    echo "Step 9: Flight Purser loads → loaded\n";
    echo "══════════════════════════════════════════════════════════════════\n\n";
    
    echo "📝 Next Steps:\n";
    echo "  1. Login as Catering Staff\n";
    echo "  2. Create a new request for a flight\n";
    echo "  3. Select products that are IN STOCK\n";
    echo "  4. Submit and test the complete workflow!\n\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    echo "\nTransaction rolled back. No data was deleted.\n";
    exit(1);
}

echo "✅ Reset complete!\n";
