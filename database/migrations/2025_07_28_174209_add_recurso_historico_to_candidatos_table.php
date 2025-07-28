<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
{
    Schema::table('candidatos', function (Blueprint $table) {
        // A linha abaixo agora será lida pelo sistema
        $table->json('recurso_historico')->nullable()->after('recurso_tipo');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidatos', function (Blueprint $table) {
            // ✅ Isto permite reverter a alteração de forma segura se for preciso.
            $table->dropColumn('recurso_historico');
        });
    }
};