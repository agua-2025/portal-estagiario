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
                // Adiciona a coluna instituicao_id como unsignedBigInteger
                // e permite que seja nula, se necessário (DEFAULT NULL)
                $table->unsignedBigInteger('instituicao_id')->nullable()->after('curso_id');
                
                // Opcional: Adicionar chave estrangeira se ainda não existir
                // Se você já tem uma FK para instituicoes, pode não precisar desta linha
                // $table->foreign('instituicao_id')->references('id')->on('instituicoes')->onDelete('set null');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('candidatos', function (Blueprint $table) {
                // Para reverter, remova a chave estrangeira primeiro (se existir)
                // $table->dropForeign(['instituicao_id']);
                // Em seguida, remova a coluna
                $table->dropColumn('instituicao_id');
            });
        }
    };
    