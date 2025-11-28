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
        Schema::table('users', function (Blueprint $table) {
            // ID único que o Google/Facebook nos dá
        $table->string('provider_id')->nullable()->after('id');
        // Nome do provedor (google, facebook)
        $table->string('provider_name')->nullable()->after('provider_id');
        // A senha passa a ser opcional (nullable) para quem logar com social
        $table->string('password')->nullable()->change();
        // Opcional: guardar o avatar do Google
        $table->string('avatar')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
