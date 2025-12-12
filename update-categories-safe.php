<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Current categories:\n\n";

$categories = DB::table('categories')->get();

foreach ($categories as $category) {
    echo "ID: {$category->id} - Name: {$category->name}\n";
}

echo "\n\nUpdating/Creating categories...\n\n";

// Update or create categories
$newCategories = [
    ['id' => 1, 'name' => 'Food', 'slug' => 'food', 'description' => 'Food items and meals'],
    ['id' => 2, 'name' => 'Drinks', 'slug' => 'drinks', 'description' => 'Beverages and drinks'],
    ['id' => 3, 'name' => 'Bites', 'slug' => 'bites', 'description' => 'Snacks and light bites'],
    ['id' => 4, 'name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Utensils and accessories'],
];

foreach ($newCategories as $cat) {
    $exists = DB::table('categories')->where('id', $cat['id'])->exists();
    
    if ($exists) {
        DB::table('categories')->where('id', $cat['id'])->update([
            'name' => $cat['name'],
            'slug' => $cat['slug'],
            'description' => $cat['description'],
            'updated_at' => now()
        ]);
        echo "✓ Updated: {$cat['name']}\n";
    } else {
        DB::table('categories')->insert([
            'id' => $cat['id'],
            'name' => $cat['name'],
            'slug' => $cat['slug'],
            'description' => $cat['description'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "✓ Created: {$cat['name']}\n";
    }
}

// Delete any categories beyond ID 4
$deleted = DB::table('categories')->where('id', '>', 4)->delete();
if ($deleted > 0) {
    echo "\n✓ Deleted $deleted old categories\n";
}

echo "\n✅ Categories updated successfully!\n\n";

echo "Final categories:\n\n";
$finalCategories = DB::table('categories')->orderBy('id')->get();
foreach ($finalCategories as $category) {
    echo "ID: {$category->id} - Name: {$category->name} - Slug: {$category->slug}\n";
}
