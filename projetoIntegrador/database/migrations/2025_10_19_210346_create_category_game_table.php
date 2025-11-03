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
        Schema::create('category_game', function (Blueprint $table) {
            
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            
            $table->primary(['category_id', 'game_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_game');
    }
};
