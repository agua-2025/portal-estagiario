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
        // Armazena a data e hora final para o candidato enviar um recurso.
        // É nullable porque só terá valor quando uma atividade for rejeitada.
        $table->timestamp('prazo_recurso_ate')->nullable()->after('motivo_rejeicao');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidato_atividades', function (Blueprint $table) {
            //
        });
    }
};
