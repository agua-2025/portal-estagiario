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
        Schema::create('candidato_atividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // A ligação mais importante: qual regra de pontuação se aplica a este item?
            $table->foreignId('tipo_de_atividade_id')->constrained('tipos_de_atividade');

            // Campo para o candidato descrever a atividade (ex: "Curso de Gestão na FGV")
            $table->string('descricao_customizada');

            // Campos para os diferentes tipos de pontuação (podem ser nulos)
            $table->integer('carga_horaria')->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();

            $table->string('path'); // O caminho para o ficheiro comprovativo
            $table->string('status')->default('enviado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidato_atividades');
    }
};
