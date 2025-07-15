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
    Schema::table('candidatos', function (Blueprint $table) {
        // Adiciona a nova coluna 'nome_completo' depois da coluna 'user_id'
        // Ela pode ser nula no início, antes de o candidato preencher.
        $table->string('nome_completo')->nullable()->after('user_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('candidatos', function (Blueprint $table) {
        // Remove a coluna se a migration for revertida
        $table->dropColumn('nome_completo');
    });
}
};
