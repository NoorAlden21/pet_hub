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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('pet_type_id')->constrained('pet_types');
            $table->foreignId('pet_breed_id')->nullable()->constrained('pet_breeds');

            $table->foreignId('appointment_category_id')->constrained('appointment_categories');

            $table->date('appointment_date');
            $table->text('notes')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'completed', 'missed'])->default('pending');

            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->index(['status', 'appointment_date', 'appointment_category_id'], "status_apoitdate_apointcateg_idx");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
