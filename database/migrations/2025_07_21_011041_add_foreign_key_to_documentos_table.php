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
        Schema::table('documentos', function (Blueprint $table) {
            // ✅ AJUSTE: Remove a chave estrangeira antiga, se ela existir.
            // O nome 'documentos_user_id_foreign' é o padrão do Laravel.
            $table->dropForeign('documentos_user_id_foreign');

            // Adiciona a nova regra de chave estrangeira com restrição.
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
        Schema::table('documentos', function (Blueprint $table) {
            // Remove a regra de chave estrangeira se precisarmos de reverter a migração.
            $table->dropForeign(['user_id']);

            // Opcional: Recria a chave estrangeira original sem a restrição
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};