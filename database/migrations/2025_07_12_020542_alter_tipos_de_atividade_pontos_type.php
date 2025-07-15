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
        Schema::table('tipos_de_atividade', function (Blueprint $table) {
            // ✅ CORREÇÃO: Altera o tipo da coluna 'pontos_por_unidade' para decimal.
            //    Este é o nome REAL da coluna no seu banco de dados.
            $table->decimal('pontos_por_unidade', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipos_de_atividade', function (Blueprint $table) {
            // Reverte o tipo para integer (pode haver perda de dados decimais se houverem valores não inteiros)
            $table->integer('pontos_por_unidade')->change();
        });
    }
};
