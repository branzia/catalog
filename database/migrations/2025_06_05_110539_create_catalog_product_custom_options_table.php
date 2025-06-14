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
        Schema::create('catalog_product_custom_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('catalog_products')->onDelete('cascade');
            $table->string('title'); // e.g., "Engraving", "Gift Note"
            $table->enum('type', ['text', 'textarea', 'file', 'select', 'radio', 'checkbox']);
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_product_custom_options');
    }
};
