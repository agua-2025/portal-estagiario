<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // Importa o modelo Role do Spatie

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Garante que o usuário admin exista, ou o cria se não existir.
        // O papel 'admin' do Spatie será atribuído neste seeder também,
        // garantindo que, independentemente da ordem de execução dos seeders,
        // o admin sempre terá o papel correto.
        $adminUser = User::firstOrCreate(
            ['email' => 'marcio@mirassoldoeste.com.br'], // <-- NOVO EMAIL AQUI
            [
                'name' => 'Márcio Admin',
                'password' => Hash::make('password'), // Altere 'password' para uma senha segura em desenvolvimento
                'role' => 'admin', // Mantém a coluna role por enquanto, conforme nossa discussão
                'email_verified_at' => now(), // Marca como verificado para facilitar login em dev
            ]
        );

        $this->command->info('Usuário admin configurado/existente: ' . $adminUser->email);

        // Atribui o papel 'admin' do Spatie, se ainda não tiver sido atribuído
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
            $this->command->info('Papel "admin" do Spatie atribuído ao usuário: ' . $adminUser->email);
        } else {
            $this->command->info('Usuário admin já possui o papel "admin" do Spatie.');
        }
    }
}