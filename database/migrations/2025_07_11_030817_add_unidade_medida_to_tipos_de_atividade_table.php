<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Usa o Schema Builder para modificar a tabela 'tipos_de_atividade'
        Schema::table('tipos_de_atividade', function (Blueprint $table) {
            
            // 1. Renomeia a coluna 'pontos' para 'pontos_por_unidade' para maior clareza.
            $table->renameColumn('pontos', 'pontos_por_unidade');

            // 2. Adiciona a coluna 'unidade_medida' para o tipo de cálculo.
            $table->enum('unidade_medida', ['fixo', 'horas', 'meses', 'media_aproveitamento'])
                  ->default('fixo')
                  ->after('descricao');

            // 3. Adiciona as novas colunas que serão usadas para os cálculos dinâmicos.
            // Elas são 'nullable' porque só serão usadas para regras de 'horas' ou 'meses'.
            $table->integer('divisor_unidade')->nullable()->after('pontos_por_unidade');
            $table->integer('pontuacao_maxima')->nullable()->after('divisor_unidade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverte todas as alterações na ordem inversa para segurança.
        Schema::table('tipos_de_atividade', function (Blueprint $table) {
            $table->dropColumn(['pontuacao_maxima', 'divisor_unidade', 'unidade_medida']);
            $table->renameColumn('pontos_por_unidade', 'pontos');
        });
    }
};