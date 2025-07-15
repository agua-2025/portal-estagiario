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
        Schema::table('cursos', function (Blueprint $table) {
            // Campos já existentes na sua migration:
            // Adiciona uma coluna 'descricao' do tipo TEXT, que pode ser nula
            $table->text('descricao')->nullable()->after('nome'); 
            
            // Adiciona uma coluna 'detalhes' do tipo TEXT, que pode ser nula
            $table->text('detalhes')->nullable()->after('descricao'); 
            
            // Novos campos solicitados e sugeridos:
            // Valor da bolsa-auxílio (DECIMAL para valores monetários)
            $table->decimal('valor_bolsa_auxilio', 8, 2)->nullable()->after('detalhes'); // 8 dígitos no total, 2 após a vírgula
            
            // Valor do auxílio-transporte (DECIMAL para valores monetários)
            $table->decimal('valor_auxilio_transporte', 8, 2)->nullable()->after('valor_bolsa_auxilio');
            
            // Requisitos para o estagiário
            $table->text('requisitos')->nullable()->after('valor_auxilio_transporte');
            
            // Outros benefícios oferecidos
            $table->text('beneficios')->nullable()->after('requisitos');
            
            // Carga horária do estágio
            $table->string('carga_horaria')->nullable()->after('beneficios'); // Ex: "6h/dia", "30h/semana"
            
            // Local de realização do estágio
            $table->string('local_estagio')->nullable()->after('carga_horaria'); // Ex: "Remoto", "Presencial - São Paulo", "Híbrido"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            // Remove as colunas se a migration for revertida
            $table->dropColumn('descricao');
            $table->dropColumn('detalhes');
            $table->dropColumn('valor_bolsa_auxilio');
            $table->dropColumn('valor_auxilio_transporte');
            $table->dropColumn('requisitos');
            $table->dropColumn('beneficios');
            $table->dropColumn('carga_horaria');
            $table->dropColumn('local_estagio');
        });
    }
};