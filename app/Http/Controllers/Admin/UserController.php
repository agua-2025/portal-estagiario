<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidato; // Para associar candidatos ao criar novos usuários
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role; // Importar o modelo Role do Spatie
use Illuminate\Support\Facades\DB; // Para transações
use Illuminate\Support\Facades\Log; // Para log de erros

class UserController extends Controller
{
    /**
     * Exibe uma lista de todos os usuários do sistema.
     */
    public function index()
    {
        // Carrega todos os usuários com seus papéis Spatie
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Mostra o formulário para criar um novo usuário.
     */
    public function create()
    {
        // Pega todos os papéis disponíveis para exibição no formulário
        $roles = Role::pluck('name', 'id'); // Retorna um array de 'nome_papel' => 'id'
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Armazena um novo usuário criado no armazenamento.
     */
    public function store(Request $request)
    {
        // Regras de validação para a criação de um novo usuário
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' exige password_confirmation
            'roles' => 'array', // Deve ser um array de IDs de papéis
            'roles.*' => 'exists:roles,id', // Cada ID no array deve existir na tabela roles
            'is_candidato' => 'boolean', // Flag para indicar se o usuário é um candidato
        ]);

        DB::beginTransaction(); // Inicia uma transação de banco de dados
        try {
            // Cria o novo usuário
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                // A coluna 'role' legada ainda será preenchida aqui
                'role' => in_array(Role::where('name', 'admin')->first()->id ?? null, $validatedData['roles'] ?? []) ? 'admin' : 'candidato',
            ]);

            // Sincroniza os papéis Spatie para o novo usuário
            if (isset($validatedData['roles'])) {
                $user->syncRoles($validatedData['roles']);
            } else {
                // Se nenhum papel foi selecionado, atribui 'estagiario' por padrão para não admin
                // Ou você pode optar por não atribuir nenhum papel por padrão
                if (!($user->hasRole('admin'))) {
                    $user->assignRole('estagiario');
                }
            }
            
            // Se o usuário foi marcado como candidato, cria um perfil de Candidato
            if (isset($validatedData['is_candidato']) && $validatedData['is_candidato']) {
                $user->assignRole('estagiario'); // Garante que o User tenha o papel 'estagiario'
                Candidato::create([
                    'user_id' => $user->id,
                    'nome_completo' => $user->name,
                    'cpf' => '000.000.000-00', // CPF placeholder, deve ser preenchido pelo candidato
                    // Outros campos obrigatórios do Candidato precisam de valores padrão ou serem coletados no formulário
                ]);
            }


            DB::commit(); // Confirma a transação
            return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            Log::error("Erro ao criar usuário: " . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao criar o usuário.')->withInput();
        }
    }

    /**
     * Exibe o perfil de um usuário específico.
     */
    public function show(User $user)
    {
        $user->load('roles'); // Carrega os papéis do Spatie
        $user->load('candidato'); // Carrega o perfil do candidato se existir
        return view('admin.users.show', compact('user'));
    }

    /**
     * Mostra o formulário para editar o usuário especificado.
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'id');
        $userRoles = $user->roles->pluck('id')->toArray(); // Pega os IDs dos papéis atuais do usuário
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Atualiza o usuário especificado no armazenamento.
     */
    public function update(Request $request, User $user)
    {
        // Regras de validação para a atualização de um usuário
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed', // Senha opcional para atualização
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            // Atualiza os dados básicos do usuário
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            // Atualiza a coluna 'role' legada baseada nos papéis Spatie
            $user->role = in_array(Role::where('name', 'admin')->first()->id ?? null, $validatedData['roles'] ?? []) ? 'admin' : ($user->candidato ? 'candidato' : null);
            
            $user->save();

            // Sincroniza os papéis Spatie para o usuário
            $user->syncRoles($validatedData['roles'] ?? []); // Se nenhum papel for selecionado, remove todos

            // Se o usuário não tiver papel de admin e tiver perfil de candidato, garanta que ele seja 'estagiario'
            if (!$user->hasRole('admin') && $user->candidato && !$user->hasRole('estagiario')) {
                 $user->assignRole('estagiario');
            }
            // Se o usuário não for mais admin, mas era estagiario, e o papel estagiario nao foi selecionado, garantir que ele ainda o tenha
            else if (!$user->hasRole('admin') && $user->candidato && !in_array(Role::where('name', 'estagiario')->first()->id ?? null, $validatedData['roles'] ?? [])) {
                $user->assignRole('estagiario');
            }


            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao atualizar usuário ID {$user->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar o usuário.')->withInput();
        }
    }

    /**
     * Remove o usuário especificado do armazenamento.
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            // Se o usuário tiver um perfil de candidato, apague-o primeiro
            if ($user->candidato) {
                $user->candidato->delete();
            }
            // Então, apague o próprio usuário
            $user->delete();

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Usuário e seus dados associados apagados com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao apagar usuário ID {$user->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao apagar o usuário.');
        }
    }
}
