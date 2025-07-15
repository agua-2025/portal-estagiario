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
        // ✅ VERIFICA SE A COLUNA JÁ EXISTE ANTES DE TENTAR ADICIONAR
        if (!Schema::hasColumn('candidato_atividades', 'status')) {
            Schema::table('candidato_atividades', function (Blueprint $table) {
                // Adiciona a coluna 'status' para guardar a validação do admin.
                // O default é 'Pendente', para que todas as novas atividades precisem de análise.
                $table->string('status')->default('Pendente')->after('path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ✅ VERIFICA SE A COLUNA EXISTE ANTES DE TENTAR REMOVER
        if (Schema::hasColumn('candidato_atividades', 'status')) {
            Schema::table('candidato_atividades', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
