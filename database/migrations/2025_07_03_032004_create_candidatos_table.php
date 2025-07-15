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
    Schema::create('candidatos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // Adicionamos ->nullable() a todos os campos que podem começar vazios
        $table->foreignId('curso_id')->nullable()->constrained('cursos');
        $table->string('cpf')->unique()->nullable();
        $table->string('nome_pai')->nullable();
        $table->string('nome_mae')->nullable();
        $table->date('data_nascimento')->nullable();
        $table->string('sexo')->nullable();
        $table->string('rg')->unique()->nullable();
        $table->string('rg_orgao_expedidor')->nullable();
        $table->string('telefone')->nullable();
        $table->string('logradouro')->nullable();
        $table->string('numero')->nullable();
        $table->string('bairro')->nullable();
        $table->string('cidade')->nullable();
        $table->string('estado')->nullable();
        $table->string('cep')->nullable();
        $table->string('naturalidade_cidade')->nullable();
        $table->string('naturalidade_estado')->nullable();
        $table->date('curso_data_inicio')->nullable();
        $table->date('curso_previsao_conclusao')->nullable();
        $table->decimal('media_aproveitamento', 4, 2)->nullable();
        $table->integer('semestres_completos')->nullable();
        $table->boolean('possui_deficiencia')->nullable();
        $table->integer('pontuacao_final')->default(0);
        $table->string('status')->default('Inscrição Incompleta');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatos');
    }
};
