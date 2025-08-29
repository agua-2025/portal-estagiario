<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            // data/hora do último contato
            $table->timestamp('last_contacted_at')->nullable()->after('updated_at');
            // canal do último contato: 'whatsapp' | 'email'
            $table->string('last_contact_via', 20)->nullable()->after('last_contacted_at');
        });
    }

    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->dropColumn(['last_contacted_at', 'last_contact_via']);
        });
    }
};
