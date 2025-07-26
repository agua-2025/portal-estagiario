<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chama nossos seeders customizados
        $this->call([
            EstadosSeeder::class,
            CidadesSeeder::class,
            AdminUserSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);

        // O User::factory()->create() para 'test@example.com' foi removido aqui
        // para evitar a criação duplicada do usuário de teste.
        // Se precisar de um usuário de teste no futuro, considere User::firstOrCreate().
    }
}
