<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Request #1 Approval Fields Check ===\n\n";

$request = App\Models\Request::find(1);

echo "Request #1 Details:\n";
echo "  Status: {$request->status}\n";
echo "  approved_by: " . ($request->approved_by ?? 'NULL') . "\n";
echo "  approved_at: " . ($request->approved_at ?? 'NULL') . "\n";
echo "  catering_approved_by: " . ($request->catering_approved_by ?? 'NULL') . "\n";
echo "  catering_approved_at: " . ($request->catering_approved_at ?? 'NULL') . "\n";
echo "  security_dispatched_by: " . ($request->security_dispatched_by ?? 'NULL') . "\n";
echo "  security_dispatched_at: " . ($request->security_dispatched_at ?? 'NULL') . "\n";
echo "  dispatched_by: " . ($request->dispatched_by ?? 'NULL') . "\n";
echo "  dispatched_at: " . ($request->dispatched_at ?? 'NULL') . "\n";
echo "  loaded_by: " . ($request->loaded_by ?? 'NULL') . "\n";
echo "  loaded_at: " . ($request->loaded_at ?? 'NULL') . "\n";

echo "\n=== Direct Database Check ===\n";
$data = DB::table('requests')->where('id', 1)->first();
echo "approved_by column: " . ($data->approved_by ?? 'NULL') . "\n";
echo "loaded_by column: " . ($data->loaded_by ?? 'NULL') . "\n";
echo "dispatched_by column: " . ($data->dispatched_by ?? 'NULL') . "\n";
