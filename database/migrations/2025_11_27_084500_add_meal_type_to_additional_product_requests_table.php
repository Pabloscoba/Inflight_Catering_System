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
        Schema::table('additional_product_requests', function (Blueprint $table) {
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack', 'VIP_meal', 'special_meal', 'non_meal'])
                  ->nullable()
                  ->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('additional_product_requests', function (Blueprint $table) {
            $table->dropColumn('meal_type');
        });
    }
};
