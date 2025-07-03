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
        Schema::create('catalog_product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_attribute_id')->constrained('catalog_product_attributes', 'id')->onDelete('cascade')->index('product_attribute_id', 'cpav_attr_id_fk'); 
            $table->foreignId('attribute_value_id')->constrained('attribute_values')->onDelete('cascade')->index('attribute_value_id', 'cpav_value_id_fk');
            $table->unique(['product_attribute_id', 'attribute_value_id'], 'uniq_product_attr_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_product_attribute_values');
    }
};
