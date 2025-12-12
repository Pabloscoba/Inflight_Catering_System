<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Current categories:\n\n";

$categories = DB::table('categories')->get();

foreach ($categories as $category) {
    echo "ID: {$category->id} - Name: {$category->name} - Slug: {$category->slug}\n";
}

echo "\n\nDeleting old categories and creating new ones...\n\n";

// Delete old categories
DB::table('categories')->truncate();

// Create new categories
$newCategories = [
    ['name' => 'Food', 'slug' => 'food', 'description' => 'Food items and meals', 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'Drinks', 'slug' => 'drinks', 'description' => 'Beverages and drinks', 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'Bites', 'slug' => 'bites', 'description' => 'Snacks and light bites', 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Utensils and accessories', 'created_at' => now(), 'updated_at' => now()],
];

foreach ($newCategories as $cat) {
    DB::table('categories')->insert($cat);
    echo "✓ Created: {$cat['name']}\n";
}

echo "\n✅ Categories updated successfully!\n";
