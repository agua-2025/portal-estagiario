<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // Importar para Rule::unique
use Illuminate\Auth\Events\Registered; // Para disparar evento ao criar usuário
use Spatie\Permission\Models\Role; // Para o Spatie
use Spatie\Permission\Models\Permission; // Para o Spatie

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Pega todos os papéis do Spatie para o filtro
        $roles = Role::all()->pluck('name', 'name')->toArray(); // Converte para array associativo

        $search = $request->input('search');
        $roleFilter = $request->input('role');

        $query = User::query()->with('roles'); // Carrega os papéis do usuário

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter && $roleFilter !== 'all') {
            $query->role($roleFilter); // Filtra por papel usando a função do Spatie
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users', 'roles', 'search', 'roleFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->toArray(); // Pega todos os nomes dos papéis
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'roles' => ['required', 'array', 'min:1'], // Pelo menos um papel deve ser selecionado
            'roles.*' => ['string', Rule::in(Role::pluck('name')->toArray())], // Garante que os papéis selecionados são válidos
            'cpf' => [
                'nullable', // Inicia como nullable
                'string',
                'max:14',
                // CPF é obrigatório e único APENAS se o papel 'estagiario' for selecionado
                Rule::when(in_array('estagiario', $request->roles), ['required', 'unique:candidatos,cpf'])
            ],
        ], [
            'cpf.required' => 'O CPF é obrigatório para usuários com papel Estagiário.',
            'cpf.unique' => 'Este CPF já está cadastrado para outro candidato.',
            'roles.required' => 'Selecione pelo menos um papel para o usuário.',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => null, // Novo usuário não verificado por padrão
            ]);

            // Atribui os papéis selecionados do Spatie
            $user->syncRoles($request->roles);

            // Se o papel 'estagiario' foi selecionado, cria ou associa o perfil de candidato
            if (in_array('estagiario', $request->roles)) {
                $candidato = Candidato::firstOrCreate(
                    ['user_id' => $user->id], // Critério de busca: user_id
                    [
                        'nome_completo' => $request->name,
                        'cpf' => $request->cpf, // Usar o CPF do formulário
                        'status' => 'Inscrição Incompleta',
                        // Outros campos do candidato podem ser adicionados aqui se forem obrigatórios
                    ]
                );
                // Se já existia (firstOrCreate) mas não estava vinculado, ou se é novo
                if (!$candidato->user_id) {
                    $candidato->user_id = $user->id;
                    $candidato->save();
                }
            }

            // Dispara o evento de Registered para enviar o email de verificação
            event(new Registered($user));

            return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso e e-mail de verificação enviado!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erro ao criar usuário: " . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao criar o usuário.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('candidato'); // Carrega o relacionamento candidato
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->toArray(); // Pega todos os nomes dos papéis
        $userRoles = $user->roles->pluck('name')->toArray(); // Pega os papéis atuais do usuário
        $user->load('candidato'); // ✅ Essencial: Carregar o relacionamento candidato aqui
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', Rule::in(Role::pluck('name')->toArray())],
            'cpf' => [
                'nullable',
                'string',
                'max:14',
                // CPF é obrigatório se o papel 'estagiario' for selecionado
                Rule::when(in_array('estagiario', $request->roles), ['required']),
                // CPF é único, ignorando o próprio candidato (se existir)
                Rule::unique('candidatos', 'cpf')->ignore($user->candidato->id ?? null),
            ],
        ];

        // Se uma nova senha for fornecida, adicione regras de validação para a senha
        if ($request->filled('password')) {
            $validationRules['password'] = ['required', 'confirmed', 'min:8'];
        }

        $request->validate($validationRules, [
            'cpf.required' => 'O CPF é obrigatório para usuários com papel Estagiário.',
            'cpf.unique' => 'Este CPF já está cadastrado para outro candidato.',
            'roles.required' => 'Selecione pelo menos um papel para o usuário.',
        ]);

        try {
            // Atualiza os dados básicos do usuário
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // Sincroniza os papéis do Spatie
            $user->syncRoles($request->roles);

            // Lógica para perfil de Candidato (se for estagiário)
            if (in_array('estagiario', $request->roles)) {
                // Se o usuário ainda não tem um perfil de candidato, cria um novo
                if (!$user->candidato) {
                    $user->candidato()->create([
                        'nome_completo' => $user->name, // Usa o nome do usuário
                        'cpf' => $request->cpf,
                        'status' => 'Inscrição Incompleta',
                        // ... outros campos obrigatórios do Candidato se houver
                    ]);
                } else {
                    // Se já tem perfil de candidato, atualiza o CPF
                    $user->candidato->cpf = $request->cpf;
                    $user->candidato->nome_completo = $user->name; // Mantém nome sincronizado
                    $user->candidato->save();
                }
            } else {
                // Se o papel 'estagiario' foi removido, verifica se o perfil de candidato deve ser deletado.
                // Apenas remova o perfil do candidato se ele realmente existir e não for necessário.
                // Isso pode ser uma decisão de negócio mais complexa, aqui vamos apenas desvincular ou remover o papel.
                // Por enquanto, o perfil de candidato pode permanecer se o admin quiser.
            }

            return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erro ao atualizar usuário ID {$user->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar o usuário.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
{
    // Impede que o usuário logado apague a si mesmo
    if (auth()->id() == $user->id) {
        return redirect()->back()->with('error', 'Você não pode apagar sua própria conta de usuário.');
    }

    // ADICIONE ESTAS LINHAS AQUI ↓
    if ($user->hasRole('admin')) {
        $adminCount = User::role('admin')->count();
        
        if ($adminCount <= 1) {
            return redirect()->back()->with('error', 'Não é possível excluir o último administrador do sistema. Nomeie outro administrador primeiro.');
        }
    }
    // ATÉ AQUI ↑

    \Illuminate\Support\Facades\DB::beginTransaction();
    try {
        // Se o usuário tem um perfil de candidato, apaga-o primeiro
        if ($user->candidato) {
            $user->candidato->delete();
        }

        // Apaga o usuário
        $user->delete();

        \Illuminate\Support\Facades\DB::commit();

        return redirect()->route('admin.users.index')->with('success', 'Usuário e dados associados apagados com sucesso!');

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack();
        \Illuminate\Support\Facades\Log::error("Erro ao apagar usuário ID {$user->id}: " . $e->getMessage());
        return redirect()->back()->with('error', 'Ocorreu um erro ao apagar o usuário.');
    }
}

    /**
     * Reenvia o email de verificação para o usuário.
     */
    public function resendVerificationEmail(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', 'O e-mail deste usuário já está verificado.');
        }

        try {
            $user->sendEmailVerificationNotification();
            return redirect()->back()->with('success', 'E-mail de verificação reenviado com sucesso para ' . $user->email . '!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erro ao reenviar email de verificação para usuário ID {$user->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao reenviar o e-mail de verificação.');
        }
    }
}