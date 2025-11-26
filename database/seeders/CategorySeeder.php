<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bites',
                'slug' => 'bites',
                'description' => 'Small snacks and bite-sized food items for quick consumption during flights',
            ],
            [
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'Main meals, entrees, and substantial food items served on flights',
            ],
            [
                'name' => 'Drinks',
                'slug' => 'drinks',
                'description' => 'Beverages including water, juices, soft drinks, tea, coffee, and alcoholic drinks',
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Cutlery, napkins, cups, plates, and other dining accessories',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
