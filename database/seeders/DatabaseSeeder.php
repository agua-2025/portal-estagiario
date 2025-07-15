<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chama nossos seeders customizados primeiro
        $this->call([
            EstadosSeeder::class,
            CidadesSeeder::class,
            AdminUserSeeder::class,
        ]);

        // O código abaixo é o padrão do Laravel para criar um usuário de teste.
        // É útil para testes e podemos mantê-lo.
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}