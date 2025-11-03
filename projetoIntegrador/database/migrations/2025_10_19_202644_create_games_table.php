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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('about');
            $table->string('cover_url');
            $table->string('video_url')->nullable();
            $table->date('release_date');
            $table->string('age_rating');

            $table->foreignId('developer_id')->constrained('developers');
            $table->foreignId('publisher_id')->constrained('publishers');
            

            $table->foreignId('base_game_id')->nullable()->constrained('games')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
