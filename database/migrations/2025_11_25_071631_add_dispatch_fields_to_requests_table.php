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
        Schema::table('requests', function (Blueprint $table) {
            $table->foreignId('dispatched_by')->nullable()->after('approved_date')->constrained('users')->onDelete('set null');
            $table->timestamp('dispatched_at')->nullable()->after('dispatched_by');
            $table->foreignId('loaded_by')->nullable()->after('dispatched_at')->constrained('users')->onDelete('set null');
            $table->timestamp('loaded_at')->nullable()->after('loaded_by');
            $table->foreignId('delivered_by')->nullable()->after('loaded_at')->constrained('users')->onDelete('set null');
            $table->timestamp('delivered_at')->nullable()->after('delivered_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['dispatched_by']);
            $table->dropColumn('dispatched_by');
            $table->dropColumn('dispatched_at');
            $table->dropForeign(['loaded_by']);
            $table->dropColumn('loaded_by');
            $table->dropColumn('loaded_at');
            $table->dropForeign(['delivered_by']);
            $table->dropColumn('delivered_by');
            $table->dropColumn('delivered_at');
        });
    }
};
