<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Possible choices (for select, radio, checkbox)
     */
    public function up(): void
    {
        Schema::create('catalog_product_customizable_options_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_id')->constrained('catalog_product_customizable_options')->onDelete('cascade');
            $table->string('label');      // e.g., "Red", "Large"
            $table->string('sku', 100)->unique();
            $table->decimal('price', 10, 2)->default(0); // optional extra price            
            $table->enum('type', ['fixed', 'percent']);
            $table->string('compatible_extensions',100)->nullable();
            $table->integer('max_characters')->default(250);
            $table->integer('sort_order')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_product_customizable_options_values');
    }
};
