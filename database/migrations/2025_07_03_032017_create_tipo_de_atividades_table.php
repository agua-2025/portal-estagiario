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
        // ESTE CÓDIGO CRIA A TABELA 'tipos_de_atividade'
        Schema::create('tipos_de_atividade', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            // ✅ IMPORTANTE: A coluna 'pontos' DEVE ser INTEGER aqui, como era originalmente.
            //    Ela será alterada para DECIMAL na próxima migração.
            $table->integer('pontos'); 
            $table->text('descricao')->nullable();
            $table->timestamps();
            // Adicione aqui QUAISQUER OUTRAS COLUNAS que você tinha originalmente nesta migração
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_de_atividade');
    }
};
