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
        Schema::table('request_items', function (Blueprint $table) {
            $table->integer('quantity_used')->default(0)->after('quantity_approved');
            $table->integer('quantity_defect')->default(0)->after('quantity_used');
            $table->integer('quantity_remaining')->nullable()->after('quantity_defect');
            $table->text('defect_notes')->nullable()->after('quantity_remaining');
            $table->text('usage_notes')->nullable()->after('defect_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_items', function (Blueprint $table) {
            $table->dropColumn(['quantity_used', 'quantity_defect', 'quantity_remaining', 'defect_notes', 'usage_notes']);
        });
    }
};
