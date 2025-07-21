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
            // Adiciona a nova coluna 'candidato_id' que pode ser nula inicialmente
            $table->unsignedBigInteger('candidato_id')->nullable()->after('user_id');

            // Adiciona a regra de chave estrangeira para a nova coluna
            $table->foreign('candidato_id')
                  ->references('id')
                  ->on('candidatos')
                  ->onDelete('cascade'); // Exclusão em cascata a partir do candidato
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidato_atividades', function (Blueprint $table) {
            $table->dropForeign(['candidato_id']);
            $table->dropColumn('candidato_id');
        });
    }
};
