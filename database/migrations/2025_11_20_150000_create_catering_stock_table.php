<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This table tracks products received by Catering Incharge from Inventory Personnel
     * Catering Incharge must approve receipt before products are available to Catering Staff
     */
    public function up(): void
    {
        Schema::create('catering_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity_received'); // Quantity received from inventory
            $table->integer('quantity_available')->default(0); // Available for catering staff (after approval)
            $table->string('reference_number')->nullable(); // Transfer reference from inventory
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('cascade'); // Inventory Personnel who sent (nullable until staff marks received)
            $table->foreignId('catering_incharge_id')->nullable()->constrained('users')->onDelete('set null'); // Catering Incharge
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('received_date');
            $table->timestamp('approved_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_stock');
    }
};
