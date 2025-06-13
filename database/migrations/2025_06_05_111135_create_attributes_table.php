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
            $table->id();
            $table->string('code', 100)->unique(); // e.g. color, size
            $table->string('label', 255);
            $table->boolean('is_required')->default(false); 
            $table->boolean('is_comparable')->default(0);
            $table->boolean('is_unique')->default(0);           
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
