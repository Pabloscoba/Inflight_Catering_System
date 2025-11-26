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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number')->unique(); // e.g., AB123
            $table->string('airline'); // Airline name
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time')->nullable();
            $table->string('origin'); // Departure airport/city
            $table->string('destination'); // Arrival airport/city
            $table->string('aircraft_type')->nullable(); // Boeing 737, Airbus A320, etc.
            $table->integer('passenger_capacity')->nullable(); // Number of seats
            $table->enum('status', ['scheduled', 'boarding', 'departed', 'arrived', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
