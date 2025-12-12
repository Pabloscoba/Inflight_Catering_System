<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Categories in database:\n";
echo "Count: " . App\Models\Category::count() . "\n\n";

if (App\Models\Category::count() > 0) {
    foreach (App\Models\Category::all() as $category) {
        echo "- {$category->name} (ID: {$category->id})\n";
    }
} else {
    echo "No categories found!\n";
}
