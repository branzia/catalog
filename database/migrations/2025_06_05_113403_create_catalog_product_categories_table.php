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
        Schema::create('catalog_product_categories', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('catalog_products')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('catalog_categories')->onDelete('cascade');
            $table->primary(['product_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_product_categories');
    }
};
