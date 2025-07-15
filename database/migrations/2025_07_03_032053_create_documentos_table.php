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
    Schema::create('documentos', function (Blueprint $table) {
        $table->id();
       
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('tipo_documento'); // Ex: 'RG', 'CPF', 'COMPROVANTE_MATRICULA'
        $table->string('path'); // O caminho para o arquivo salvo no servidor
        $table->string('nome_original'); // O nome original do arquivo do usuário
        $table->string('status')->default('enviado'); // 'enviado', 'aprovado', 'rejeitado'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
