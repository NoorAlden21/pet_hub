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
        Schema::create('boarding_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->foreignId('pet_type_id')->constrained('pet_types')->cascadeOnDelete();
            $table->foreignId('pet_breed_id')->nullable()->constrained('pet_breeds')->nullOnDelete();
            $table->unsignedInteger('age_months')->nullable();

            $table->dateTime('start_at');
            $table->dateTime('end_at');

            $table->unsignedInteger('billable_hours');

            $table->enum('status', ['pending', 'confirmed', 'rejected', 'cancelled', 'completed'])
                ->default('pending');

            $table->decimal('total', 18, 2)->default(0);

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boarding_reservations');
    }
};
