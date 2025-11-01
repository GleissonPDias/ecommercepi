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
        Schema::create('carousel_slide_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carousel_slide_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
        
        // Esta é a coluna que guarda 'large', 'small_1', 'small_2'
            $table->string('slot'); 
        
        // Garantia: Um slide não pode ter dois produtos no mesmo slot
            $table->unique(['carousel_slide_id', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousel_slide_product');
    }
};
