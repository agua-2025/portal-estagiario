<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Importa o modelo User
use App\Models\Candidato; // Importa o modelo Candidato
use Spatie\Permission\Models\Role; // Importa o modelo Role do Spatie
use Spatie\Permission\Models\Permission; // Importa o modelo Permission do Spatie
use Illuminate\Support\Facades\DB; // Para transações
use Carbon\Carbon; // Para a data de verificação de e-mail

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpa o cache de permissões do Spatie antes de qualquer coisa
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Inicia uma transação para garantir que tudo seja salvo ou nada seja salvo
        DB::transaction(function () {

            $this->command->info('Configurando Roles e Permissões Spatie...');

            // Definição das permissões com base no que conversamos
            $permissions = [
                // Permissões Gerais do Sistema
                'ver_dashboard_admin',
                'gerenciar_configuracoes_sistema',
                'ver_relatorios_gerais',

                // Permissões de Usuários/Acesso
                'gerenciar_usuarios', // Inclui criar, ver, editar, deletar qualquer usuário (admin e candidato)
                'ver_proprio_perfil',
                'editar_proprio_perfil',
                'ver_todos_candidatos', // Renomeado para alinhar com o papel
                'editar_candidatos', // Renomeado para alinhar com o papel
                'homologar_candidatos', // Renomeado para alinhar com o papel
                'gerenciar_recursos_candidatos', // Renomeado para alinhar com o papel

                // Permissões de Documentos e Atividades do Candidato
                'gerenciar_documentos_candidatos', // Inclui aprovar/rejeitar
                'gerenciar_atividades_candidatos', // Inclui aprovar/rejeitar
                'enviar_proprios_documentos',
                'enviar_proprias_atividades',

                // Permissões de Instituições e Cursos
                'gerenciar_instituicoes',
                'gerenciar_cursos',
            ];

            // Cria ou encontra as permissões
            foreach ($permissions as $permission) {
                Permission::findOrCreate($permission);
            }
            $this->command->info('Permissões criadas/verificadas.');

            // Cria ou encontra os papéis
            $adminRole = Role::findOrCreate('admin');
            $candidatoRole = Role::findOrCreate('candidato'); // <-- CORRIGIDO AQUI
            $this->command->info('Papéis "admin" e "candidato" criados/verificados.');

            // Atribui todas as permissões ao papel 'admin'
            $adminRole->givePermissionTo(Permission::all());
            $this->command->info('Todas as permissões atribuídas ao papel "admin".');

            // Atribui permissões específicas ao papel 'candidato'
            $candidatoRole->givePermissionTo([
                'ver_proprio_perfil',
                'editar_proprio_perfil',
                'enviar_proprios_documentos',
                'enviar_proprias_atividades',
            ]);
            $this->command->info('Permissões específicas atribuídas ao papel "candidato".');

            // --- ATRIBUINDO PAPÉIS AOS USUÁRIOS EXISTENTES ---

            // 1. Encontra e atribui o papel 'admin' ao seu usuário administrador
            // ✅ AJUSTADO AQUI PARA O E-MAIL CORRETO
            $adminUser = User::where('email', 'marcio@mirassoldoeste.mt.gov.br')->first(); 

            if ($adminUser) {
                if (!$adminUser->hasRole('admin')) {
                    $adminUser->assignRole('admin');
                    $this->command->info('Papel "admin" atribuído ao usuário admin: ' . $adminUser->email);
                } else {
                    $this->command->info('Usuário admin com email \'' . $adminUser->email . '\' já possui o papel \'admin\'.');
                }
            } else {
                // A mensagem de erro agora usa o e-mail correto
                $this->command->error('Usuário admin com email \'marcio@mirassoldoeste.mt.gov.br\' não encontrado. Não foi possível atribuir o papel \'admin\'.');
            }

            // 2. Atribui o papel 'candidato' a todos os usuários que têm um perfil de candidato
            $candidatoUsers = User::whereHas('candidato')->get();

            foreach ($candidatoUsers as $candidatoUser) {
                if (!$candidatoUser->hasRole('candidato')) { // <-- CORRIGIDO AQUI
                    $candidatoUser->assignRole('candidato'); // <-- CORRIGIDO AQUI
                    $this->command->info('Papel "candidato" atribuído ao usuário candidato: ' . $candidatoUser->email);
                } else {
                    $this->command->info('Usuário candidato já possui o papel \'candidato\': ' . $candidatoUser->email);
                }
            }

            $this->command->info('Roles e Permissões Spatie configuradas e atribuídas aos usuários existentes com sucesso!');
        }); // Fim da transação
    }
}
