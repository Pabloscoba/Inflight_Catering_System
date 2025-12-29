<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Request;
use App\Models\RequestItem;
use App\Models\StockMovement;
use App\Models\Activity;

echo "=== RESET REQUESTS - DELETE ALL REQUESTS ===\n\n";

// Confirm before deleting
echo "⚠️  WARNING: This will delete ALL requests and related data!\n";
echo "This includes:\n";
echo "  - All requests\n";
echo "  - All request items\n";
echo "  - All stock movements related to requests\n";
echo "  - All activity logs related to requests\n\n";

echo "Do you want to continue? (yes/no): ";
$handle = fopen("php://stdin", "r");
$confirmation = trim(fgets($handle));
fclose($handle);

if (strtolower($confirmation) !== 'yes') {
    echo "\n❌ Operation cancelled.\n";
    exit(0);
}

echo "\n🔄 Starting deletion process...\n\n";

try {
    DB::beginTransaction();
    
    // Get counts before deletion
    $requestCount = Request::count();
    $requestItemCount = RequestItem::count();
    $stockMovementCount = StockMovement::whereNotNull('request_id')->count();
    
    echo "📊 Current Data:\n";
    echo "  - Requests: {$requestCount}\n";
    echo "  - Request Items: {$requestItemCount}\n";
    echo "  - Stock Movements (related to requests): {$stockMovementCount}\n\n";
    
    // Delete in correct order (respect foreign key constraints)
    
    // 1. Delete stock movements related to requests
    echo "🗑️  Deleting stock movements related to requests...\n";
    $deleted = StockMovement::whereNotNull('request_id')->delete();
    echo "   ✅ Deleted {$deleted} stock movements\n\n";
    
    // 2. Delete request items
    echo "🗑️  Deleting request items...\n";
    $deleted = RequestItem::truncate();
    echo "   ✅ Deleted all request items\n\n";
    
    // 3. Delete activity logs related to requests
    echo "🗑️  Deleting activity logs...\n";
    $deleted = Activity::where('subject_type', 'App\\Models\\Request')->delete();
    echo "   ✅ Deleted {$deleted} activity logs\n\n";
    
    // 4. Delete notifications related to requests (optional)
    echo "🗑️  Deleting notifications related to requests...\n";
    $deleted = DB::table('notifications')
        ->where('type', 'LIKE', '%Request%')
        ->delete();
    echo "   ✅ Deleted {$deleted} notifications\n\n";
    
    // 5. Finally delete requests
    echo "🗑️  Deleting all requests...\n";
    $deleted = Request::truncate();
    echo "   ✅ Deleted all requests\n\n";
    
    DB::commit();
    
    echo "✅ SUCCESS! All requests and related data have been deleted.\n\n";
    
    // Verify deletion
    echo "📊 Verification:\n";
    echo "  - Requests remaining: " . Request::count() . "\n";
    echo "  - Request Items remaining: " . RequestItem::count() . "\n";
    echo "  - Stock Movements (request-related) remaining: " . StockMovement::whereNotNull('request_id')->count() . "\n\n";
    
    echo "✨ You can now start fresh with the new workflow!\n";
    echo "\nNext steps:\n";
    echo "  1. Login as Catering Staff\n";
    echo "  2. Create a new request\n";
    echo "  3. Test the complete 9-step workflow:\n";
    echo "     Step 1: Catering Staff creates request\n";
    echo "     Step 2: Catering Incharge approves (first approval)\n";
    echo "     Step 3: Inventory Supervisor approves\n";
    echo "     Step 4: Inventory Personnel issues items\n";
    echo "     Step 5: Catering Staff receives items\n";
    echo "     Step 6: Catering Incharge gives final approval\n";
    echo "     Step 7: Security authenticates\n";
    echo "     Step 8: Ramp Dispatcher dispatches\n";
    echo "     Step 9: Flight Purser loads to aircraft\n\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Transaction rolled back. No data was deleted.\n";
    exit(1);
}

echo "✅ Reset complete!\n";
