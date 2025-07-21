<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Necessário para DB::statement

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            // Altera a coluna 'revert_reason' de string para text
            // Isso permite armazenar JSON com múltiplos motivos
            // Se o seu MySQL for 5.7.8+ ou MariaDB 10.2.7+, você pode usar $table->json('revert_reason')->nullable()->change();
            // Mas 'text' é mais universal e podemos gerenciar o JSON via PHP.
            $table->text('revert_reason')->nullable()->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            // Reverte o tipo da coluna para string (se necessário)
            $table->string('revert_reason', 255)->nullable()->change(); 
        });
    }
};