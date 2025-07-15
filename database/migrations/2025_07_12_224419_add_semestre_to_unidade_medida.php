<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Adicionado para DB::statement

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Altera a coluna 'unidade_medida' para aceitar 'semestre'
        // Se a coluna for um ENUM, esta é a forma de adicionar um novo valor.
        // Se for VARCHAR, esta migração não é estritamente necessária, mas não faz mal.
        DB::statement("ALTER TABLE tipos_de_atividade MODIFY unidade_medida ENUM('horas', 'meses', 'fixo', 'semestre') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverte a coluna para o estado anterior (removendo 'semestre')
        DB::statement("ALTER TABLE tipos_de_atividade MODIFY unidade_medida ENUM('horas', 'meses', 'fixo') NOT NULL");
    }
};
