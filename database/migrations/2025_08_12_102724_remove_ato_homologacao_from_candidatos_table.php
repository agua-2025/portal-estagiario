<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->dropColumn('ato_homologacao');
        });
    }

    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->string('ato_homologacao')->nullable()->after('recurso_historico');
        });
    }
};