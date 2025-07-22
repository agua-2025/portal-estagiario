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
            // 1. Remove a chave estrangeira antiga, se ela existir.
            $table->dropForeign(['candidato_id']);

            // 2. Adiciona a nova regra de chave estrangeira com RESTRIÇÃO.
            $table->foreign('candidato_id')
                  ->references('id')
                  ->on('candidatos')
                  ->onDelete('restrict'); // PROÍBE a exclusão se houver atividades
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidato_atividades', function (Blueprint $table) {
            // Reverte para a regra antiga (cascade) se necessário
            $table->dropForeign(['candidato_id']);
            $table->foreign('candidato_id')
                  ->references('id')
                  ->on('candidatos')
                  ->onDelete('cascade');
        });
    }
};