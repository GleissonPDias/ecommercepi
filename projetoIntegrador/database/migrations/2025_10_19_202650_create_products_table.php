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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->foreignId('platform_id')->constrained('platforms');

            $table->string('name');
            $table->decimal('default_price', 8, 2);
            $table->decimal('current_price', 8, 2);
            $table->boolean('is_active')->default(true);

            $table->boolean('is_featured_main')->default(false);
            $table->boolean('is_featured_secondary')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
