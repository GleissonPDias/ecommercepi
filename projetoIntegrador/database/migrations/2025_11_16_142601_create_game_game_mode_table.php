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
        Schema::create('game_game_mode', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->foreignId('game_mode_id')->constrained('game_modes')->onDelete('cascade');
            $table->timestamps();

            // Evita entradas duplicadas (ex: Jogo 1 - Modo 1)
            $table->unique(['game_id', 'game_mode_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_game_mode');
    }
};
