<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // ✅ Essencial para manipular os dados

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Atualiza a tabela de documentos
        DB::table('documentos as d')
            ->join('candidatos as c', 'd.user_id', '=', 'c.user_id')
            ->update(['d.candidato_id' => DB::raw('c.id')]);

        // Atualiza a tabela de atividades
        DB::table('candidato_atividades as ca')
            ->join('candidatos as c', 'ca.user_id', '=', 'c.user_id')
            ->update(['ca.candidato_id' => DB::raw('c.id')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Se precisarmos de reverter, apenas definimos as colunas como nulas novamente.
        DB::table('documentos')->update(['candidato_id' => null]);
        DB::table('candidato_atividades')->update(['candidato_id' => null]);
    }
};