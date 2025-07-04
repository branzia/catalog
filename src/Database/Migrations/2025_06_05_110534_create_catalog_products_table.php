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
        Schema::create('catalog_products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug')->unique();
            $table->enum('product_type', ['simple', 'virtual','configurable','grouped','bundle','subscription'])->default('simple');
            $table->foreignId('parent_id')->nullable()->constrained('catalog_products', 'id')->onDelete('cascade');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('sku', 100)->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('special_price', 10, 2)->nullable();
            $table->date('special_price_from')->nullable();
            $table->date('special_price_to')->nullable();
            $table->enum('visibility', ['not_visible', 'catalog', 'search', 'catalog_search'])->default('catalog_search');
            $table->date('new_from')->nullable();
            $table->date('new_to')->nullable();
            $table->decimal('weight', 8, 3)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_giftable')->default(false);
            $table->foreignId('tax_class_id')->nullable()->constrained('tax_classes');
            $table->json('attributes')->nullable();     
            $table->string('attribute_hash')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_products');
    }
};
