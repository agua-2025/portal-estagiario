<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Seeders que NÃO dependem de roles/permissions primeiro
            EstadosSeeder::class,
            CidadesSeeder::class,
            
            // CRUCIAL: RolesAndPermissionsSeeder DEVE ser chamado ANTES de qualquer seeder que ATRIBUA roles
            RolesAndPermissionsSeeder::class, // <-- ESTE DEVE VIR PRIMEIRO PARA ROLES/PERMISSIONS

            // Seeders que dependem de roles/permissions ou atribuem roles
            AdminUserSeeder::class, // <-- ESTE VEM DEPOIS QUE AS ROLES FORAM CRIADAS
        ]);

        // Removido o Test User aqui conforme sua preferência
    }
}

