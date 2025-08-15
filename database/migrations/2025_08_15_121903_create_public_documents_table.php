<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');                 // título visível no site
            $table->string('type')->nullable();      // 'edital' | 'manual' | 'cronograma' | null
            $table->string('file_path');             // caminho no storage (ex: public/docs/arquivo.pdf)
            $table->unsignedBigInteger('file_size')->nullable(); // tamanho do arquivo em bytes
            $table->timestamp('published_at')->nullable();       // data/hora de publicação
            $table->boolean('is_published')->default(true);      // publicado (true) ou rascunho (false)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_documents');
    }
};
