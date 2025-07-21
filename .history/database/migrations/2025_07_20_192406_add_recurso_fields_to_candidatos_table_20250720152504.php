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
    Schema::table('candidatos', function (Blueprint $table) {
        // Armazena o texto do recurso enviado pelo candidato.
        $table->text('recurso_texto')->nullable()->after('admin_observacao');
        // Armazena o prazo final para o candidato enviar o recurso.
        $table->timestamp('recurso_prazo_ate')->nullable()->after('recurso_texto');
        // Controla o status do recurso (ex: 'pendente', 'em_analise', 'deferido', 'indeferido').
        $table->string('recurso_status')->nullable()->after('recurso_prazo_ate');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            //
        });
    }
};
