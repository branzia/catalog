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
        Schema::create('catalog_product_relations', function (Blueprint $table) {
            $table->integer('parent_id')->unsigned();
            $table->integer('associate_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('catalog_products')->onDelete('cascade');
            $table->foreign('associate_id')->references('id')->on('catalog_products')->onDelete('cascade');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_product_relations');
    }
};
