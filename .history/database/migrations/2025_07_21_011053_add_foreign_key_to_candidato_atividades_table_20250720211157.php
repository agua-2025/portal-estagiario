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
            // Adiciona a regra de chave estrangeira.
            // onDelete('restrict') diz ao banco de dados para PROIBIR a exclusão
            // de um 'user' se ele ainda tiver atividades associadas.
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidato_atividades', function (Blueprint $table) {
            // Remove a regra de chave estrangeira se precisarmos de reverter a migração.
            $table->dropForeign(['user_id']);
        });
    }
};