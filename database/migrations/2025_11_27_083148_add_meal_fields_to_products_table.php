<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // i) Meal Types (breakfast, lunch, dinner, VIP, special)
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack', 'VIP_meal', 'special_meal'])->nullable()->after('description');
            
            // ii) Recipe & Ingredients Information
            $table->text('ingredients')->nullable()->after('meal_type');
            $table->text('allergen_info')->nullable()->after('ingredients');
            $table->string('portion_size')->nullable()->after('allergen_info');
            
            // iii) Seasonal & Route-based menu
            $table->string('season')->nullable()->after('portion_size'); // summer, winter, all-year
            $table->string('route')->nullable()->after('season'); // DAR-JRO, ZNZ-DAR, etc.
            
            // iv) Special meal specifications
            $table->boolean('is_special_meal')->default(false)->after('route');
            $table->text('special_requirements')->nullable()->after('is_special_meal');
            
            // v) Menu versions with effective dates
            $table->string('menu_version')->nullable()->after('special_requirements');
            $table->date('effective_start_date')->nullable()->after('menu_version');
            $table->date('effective_end_date')->nullable()->after('effective_start_date');
            
            // vi) Additional meal information
            $table->string('photo')->nullable()->after('effective_end_date');
            $table->text('preparation_instructions')->nullable()->after('photo');
            $table->text('nutritional_info')->nullable()->after('preparation_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'meal_type',
                'ingredients',
                'allergen_info',
                'portion_size',
                'season',
                'route',
                'is_special_meal',
                'special_requirements',
                'menu_version',
                'effective_start_date',
                'effective_end_date',
                'photo',
                'preparation_instructions',
                'nutritional_info',
            ]);
        });
    }
};
