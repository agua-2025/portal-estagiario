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
        // Note que corrigimos o nome da tabela para o plural correto em português
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->id(); // Cria a coluna de ID auto-incremental
            $table->string('nome');
            $table->string('sigla')->nullable(); // A sigla pode ser opcional
            $table->string('endereco');
            $table->string('cidade');
            $table->string('estado');
            $table->string('telefone_contato');
            $table->timestamps(); // Cria as colunas created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Corrigimos o nome aqui também
        Schema::dropIfExists('instituicoes');
    }
};