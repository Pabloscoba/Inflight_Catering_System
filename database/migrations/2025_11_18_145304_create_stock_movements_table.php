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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['incoming', 'issued', 'returned']); // Movement type
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // Positive for incoming/returned, negative for issued
            $table->string('reference_number')->nullable(); // Invoice, Flight number, etc
            $table->text('notes')->nullable(); // Additional details
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who made the transaction
            $table->date('movement_date'); // Date of transaction
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
