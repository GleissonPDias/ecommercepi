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
        Schema::create('game_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');

            $table->text('key_value');

            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('order_item_id')->nullable()->constrained('order_items');
            $table->boolean('is_sold')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_keys');
    }
};
