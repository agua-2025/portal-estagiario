<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações do banco de dados.
     * Este método é chamado quando você executa 'php artisan migrate'.
     * Ele cria a tabela 'pages'.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id(); // Coluna de ID auto-incrementável (chave primária)
            $table->string('slug')->unique(); // Identificador único e amigável para a URL (ex: 'politica-de-privacidade')
            $table->string('title'); // Título da página (ex: 'Política de Privacidade')
            $table->longText('content'); // Conteúdo HTML da página (usamos longText para textos longos)
            $table->timestamps(); // Adiciona as colunas 'created_at' e 'updated_at' automaticamente
        });
    }

    /**
     * Reverte as migrações do banco de dados.
     * Este método é chamado quando você executa 'php artisan migrate:rollback'.
     * Ele remove a tabela 'pages'.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages'); // Remove a tabela se a migração for revertida
    }
};

