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
        Schema::create('boarding_reservation_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('boarding_reservation_id')
                ->constrained('boarding_reservations')
                ->cascadeOnDelete();

            $table->foreignId('boarding_service_id')
                ->nullable()
                ->constrained('boarding_services')
                ->nullOnDelete();

            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();

            $table->unique(
                ['boarding_reservation_id', 'boarding_service_id'],
                'brs_reservation_service_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boarding_reservation_services');
    }
};
