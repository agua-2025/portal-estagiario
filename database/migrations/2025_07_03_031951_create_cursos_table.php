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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');

            // --- A Ligação com a Tabela de Instituições ---
            // Cria a coluna 'instituicao_id' e a define como uma chave estrangeira
            // que se conecta à coluna 'id' na tabela 'instituicoes'.
            $table->foreignId('instituicao_id')->constrained('instituicoes');
            // ---------------------------------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
