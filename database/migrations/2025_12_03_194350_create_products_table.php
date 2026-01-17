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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->foreignId('pet_type_id')->nullable()->constrained('pet_types')->nullOnDelete();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_category_id', 'name_en']);
            $table->unique(['product_category_id', 'name_ar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
