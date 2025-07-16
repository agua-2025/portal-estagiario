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
                // Adiciona uma coluna 'icone_svg' do tipo TEXT, que pode ser nula
                // Armazenará o código SVG completo do ícone
                $table->text('icone_svg')->nullable()->after('nome'); 
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('cursos', function (Blueprint $table) {
                // Remove a coluna se a migration for revertida
                $table->dropColumn('icone_svg');
            });
        }
    };
    