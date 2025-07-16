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
            Schema::table('cursos', function (Blueprint $table) {
                // Primeiro, remova a chave estrangeira, se ela existir
                $table->dropForeign(['instituicao_id']);
                // Em seguida, remova a coluna
                $table->dropColumn('instituicao_id');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('cursos', function (Blueprint $table) {
                // Para reverter, adicionamos a coluna de volta
                // Certifique-se de que a tabela 'instituicoes' exista antes de reverter!
                $table->unsignedBigInteger('instituicao_id')->after('local_estagio'); // Ou onde ela estava antes
                // E então adicionamos a chave estrangeira de volta
                $table->foreign('instituicao_id')->references('id')->on('instituicoes');
            });
        }
    };