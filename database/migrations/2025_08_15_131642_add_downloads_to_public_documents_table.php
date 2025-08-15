<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('public_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('downloads')->default(0)->after('is_published');
        });
    }
    public function down(): void {
        Schema::table('public_documents', function (Blueprint $table) {
            $table->dropColumn('downloads');
        });
    }
};
