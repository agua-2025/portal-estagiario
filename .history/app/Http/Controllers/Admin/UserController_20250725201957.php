<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Importa o modelo User
use App\Models\Candidato; // Importa o modelo Candidato
use Spatie\Permission\Models\Role; // Importa o modelo Role do Spatie
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Para hashing de senhas
use Illuminate\Validation\Rule; // Para regras de validação customizadas
use Illuminate\Support\Facades\DB; // Para transações
use Carbon\Carbon; // Para carimbar datas

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carrega todos os usuários com seus papéis Spatie
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Busca todos os papéis disponíveis no Spatie
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array', // Deve ser um array de nomes de papéis
            'roles.*' => ['string', Rule::exists('roles', 'name')], // Cada item do array deve existir na tabela 'roles'
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email deve ser um endereço de e-mail válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            'password.confirmed' => 'A confirmação de senha não corresponde.',
            'roles.array' => 'Os papéis devem ser fornecidos como um array.',
            'roles.*.exists' => 'Um ou mais papéis selecionados são inválidos.',
        ]);

        DB::beginTransaction(); // Inicia a transação de banco de dados

        try {
            // Cria o usuário
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'email_verified_at' => Carbon::now(), // Marca como verificado para facilitar o acesso do admin
                // Não definimos a coluna 'role' legada aqui, ela será tratada pelos papéis Spatie
            ]);

            // Atribui os papéis Spatie
            if (isset($validatedData['roles'])) {
                $user->syncRoles($validatedData['roles']);
            } else {
                // Se nenhum papel for selecionado, atribui o papel 'estagiario' por padrão (ou o que for mais adequado)
                $user->assignRole('estagiario'); 
            }

            // Se o usuário criado tiver o papel 'estagiario', criar um registro Candidato associado
            // Nota: Se você não quer que o admin crie perfis de candidato por aqui,
            // pode remover esta seção ou adicionar uma checkbox no formulário para controlar isso.
            if ($user->hasRole('estagiario')) {
                // Validação e criação de Candidato, se necessário
                // Aqui, apenas criamos um registro básico. O perfil completo seria preenchido pelo próprio estagiário.
                $candidato = Candidato::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nome_completo' => $user->name,
                        'cpf' => '000.000.000-00', // <-- ATENÇÃO: Placeholder! Precisa ser gerado ou coletado
                        'status' => 'Inscrição Incompleta',
                        // Preencha outros campos obrigatórios do Candidato aqui se souber
                    ]
                );
                // ATENÇÃO: O CPF aqui é um placeholder. Você precisará coletá-lo no formulário
                // ou gerá-lo de forma única, pois ele é NOT NULL e UNIQUE na tabela `candidatos`.
                // A validação de `unique:candidatos,cpf` para este caso também precisaria ser adicionada
                // se o CPF for coletado no formulário de criação de usuário.
            }

            DB::commit(); // Confirma a transação

            return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            \Illuminate\Support\Facades\Log::error("Erro ao criar usuário: " . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao criar o usuário.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles'); // Carrega os papéis do Spatie
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all(); // Busca todos os papéis disponíveis
        $user->load('roles'); // Carrega os papéis que o usuário já possui
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => ['string', Rule::exists('roles', 'name')],
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email deve ser um endereço de e-mail válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            'password.confirmed' => 'A confirmação de senha não corresponde.',
            'roles.array' => 'Os papéis devem ser fornecidos como um array.',
            'roles.*.exists' => 'Um ou mais papéis selecionados são inválidos.',
        ]);

        DB::beginTransaction();

        try {
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->save();

            // Sincroniza os papéis Spatie (desatribui os antigos e atribui os novos)
            // Se nenhum papel for selecionado, remove todos os papéis (syncRoles com array vazio)
            $user->syncRoles($validatedData['roles'] ?? []);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Erro ao atualizar usuário ID {$user->id}: " . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar o usuário.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Previne que o próprio usuário logado tente se deletar
        if (auth()->user()->id === $user->id) {
            return redirect()->back()->with('error', 'Você não pode excluir sua própria conta de administrador.');
        }

        DB::beginTransaction();
        try {
            // Se o usuário tem um perfil de candidato, o perfil Candidato deve ser deletado primeiro
            // Para garantir a integridade referencial, se `user_id` no Candidato é FK
            if ($user->candidato) {
                $user->candidato->delete();
            }

            $user->delete(); // Deleta o usuário
            
            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Usuário e dados associados (se houver) apagados com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Erro ao apagar usuário ID {$user->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao apagar o usuário.');
        }
    }
}
