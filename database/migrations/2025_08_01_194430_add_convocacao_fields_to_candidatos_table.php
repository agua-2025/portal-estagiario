<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// O nome da classe precisa ser exatamente este para o Laravel encontrar
class AddConvocacaoFieldsToCandidatosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            // Data em que a convocação foi feita
            $table->timestamp('convocado_em')->nullable()->after('homologado_em');

            // Campos de lotação que você pediu
            $table->string('lotacao_local')->nullable()->after('convocado_em');
            $table->string('lotacao_chefia')->nullable()->after('lotacao_local');
            $table->text('lotacao_observacoes')->nullable()->after('lotacao_chefia');

            // Datas do contrato
            $table->date('contrato_data_inicio')->nullable()->after('lotacao_observacoes');
            $table->date('contrato_data_fim')->nullable()->after('contrato_data_inicio');
            
            // Datas da prorrogação (opcionais, por isso nullable)
            $table->date('prorrogacao_data_inicio')->nullable()->after('contrato_data_fim');
            $table->date('prorrogacao_data_fim')->nullable()->after('prorrogacao_data_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->dropColumn([
                'convocado_em',
                'lotacao_local',
                'lotacao_chefia',
                'lotacao_observacoes',
                'contrato_data_inicio',
                'contrato_data_fim',
                'prorrogacao_data_inicio',
                'prorrogacao_data_fim',
            ]);
        });
    }
}