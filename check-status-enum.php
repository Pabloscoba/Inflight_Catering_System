<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECK STATUS ENUM VALUES ===\n\n";

$result = DB::select("SHOW COLUMNS FROM requests WHERE Field = 'status'");

if ($result) {
    echo "Status column type:\n";
    echo $result[0]->Type . "\n\n";
    
    // Check if pending_catering_incharge is in the enum
    if (str_contains($result[0]->Type, 'pending_catering_incharge')) {
        echo "✅ 'pending_catering_incharge' is in the ENUM\n";
    } else {
        echo "❌ 'pending_catering_incharge' is NOT in the ENUM\n";
        echo "\n💡 Need to run migration to add 9-step workflow statuses.\n";
    }
}
