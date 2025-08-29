<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Schema; // opcional se precisar do defaultStringLength

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1) Ajusta o timezone do PHP/Carbon com base na config/env
        $tz = config('app.timezone', 'America/Cuiaba');
        @date_default_timezone_set($tz);

        // 2) Ajusta o timezone da SESSÃO do MySQL para o mesmo offset do app
        // (usa offset tipo -04:00, que funciona mesmo se as time zone tables do MySQL não estiverem carregadas)
        try {
            $offset = now()->format('P'); // ex.: "-04:00"
            DB::statement("SET time_zone = '{$offset}'");
        } catch (\Throwable $e) {
            // Ignora se não for MySQL, se a conexão ainda não existir, ou se o host bloquear o comando
        }

        // 3) (Opcional) Se tiver erro de índice em MySQL antigo, descomente:
        // Schema::defaultStringLength(191);
    }
}
