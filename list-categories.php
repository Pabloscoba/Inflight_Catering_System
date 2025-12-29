<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$categories = \App\Models\Category::all();

echo "Categories:\n";
foreach ($categories as $cat) {
    echo $cat->id . " - " . $cat->name . " (" . $cat->slug . ")\n";
}
