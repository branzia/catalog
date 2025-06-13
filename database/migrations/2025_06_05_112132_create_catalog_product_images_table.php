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
        Schema::create('catalog_product_images', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('path');
            $table->integer('product_id')->unsigned();
            $table->integer('position')->default(0)->unsigned();
            $table->foreign('product_id')->references('id')->on('catalog_products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_product_images');
    }
};
