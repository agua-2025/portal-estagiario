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
            // Adiciona a nova coluna 'semestres_declarados' como inteiro e nula
            $table->integer('semestres_declarados')->nullable()->after('data_fim'); // Colocado após data_fim
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidato_atividades', function (Blueprint $table) {
            // Remove a coluna caso a migração seja revertida
            $table->dropColumn('semestres_declarados');
        });
    }
};
