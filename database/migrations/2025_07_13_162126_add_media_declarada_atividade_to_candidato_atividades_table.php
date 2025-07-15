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
        Schema::table('candidato_atividades', function (Blueprint $table) {
            // Adiciona a nova coluna 'media_declarada_atividade' como decimal e nula
            // decimal(4, 2) permite valores como X.XX (ex: 7.50, 9.99)
            $table->decimal('media_declarada_atividade', 4, 2)->nullable()->after('semestres_declarados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidato_atividades', function (Blueprint $table) {
            // Remove a coluna caso a migração seja revertida
            $table->dropColumn('media_declarada_atividade');
        });
    }
};
