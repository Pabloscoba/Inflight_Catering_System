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
            // Catering mini stock - separate from main inventory stock
            $table->integer('catering_stock')->default(0)->after('quantity_in_stock');
            $table->integer('catering_reorder_level')->default(10)->after('catering_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('catering_stock');
            $table->dropColumn('catering_reorder_level');
        });
    }
};
