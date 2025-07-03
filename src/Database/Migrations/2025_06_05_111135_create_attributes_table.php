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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED
            $table->string('code', 100)->unique(); // e.g. color, size
            $table->string('name', 255);
            $table->string('type',20)->default('dropdown');
            $table->enum('field_type', ['single', 'multiple'])->default('single');
            $table->boolean('use_product_image_for_swatch')->default(0);
            $table->boolean('is_configurable')->default(0);
            $table->boolean('is_comparable')->default(0);
            $table->boolean('is_filterable')->default(0);
            $table->boolean('is_visible_on_front')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
