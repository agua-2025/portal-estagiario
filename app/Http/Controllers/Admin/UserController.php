<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Para hashing de senhas
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role; // Para Spatie Roles
use App\Models\Candidato; // Para criar o Candidato associado
use Illuminate\Support\Facades\Log; // Para logs de aviso

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filtros da requisição
        $search = $request->input('search');
        $roleFilter = $request->input('role_filter');

        $query = User::query();

        // Aplicar filtro de busca por nome ou e-mail
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Aplicar filtro por papel (role)
        if ($roleFilter) {
            $query->role($roleFilter); // Método do Spatie para filtrar por papel
        }

        // Ordenar e paginar resultados
        $users = $query->orderBy('name')->paginate(15)->withQueryString(); // Adiciona query string para manter filtros na paginação

        // Obter todos os papéis disponíveis para o filtro de dropdown (Spatie)
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'search', 'roleFilter', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all(); // Obtém todos os papéis do Spatie
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['array'], // Deve ser um array de roles (checkboxes)
            'roles.*' => ['exists:roles,name'], // Cada role no array deve existir na tabela roles
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => in_array('admin', $request->input('roles', [])) ? 'admin' : 'candidato', // Lógica para compatibilidade com a coluna 'role' legada
            'terms_accepted_at' => now(), // Assume que ao criar pelo admin, ele aceita os termos
        ]);

        // Atribuir papéis Spatie
        $user->assignRole($request->input('roles', []));

        // Se o usuário tiver o papel 'estagiario', cria um registro na tabela 'candidatos'
        if ($user->hasRole('estagiario')) {
            Candidato::create([
                'user_id' => $user->id,
                'nome_completo' => $user->name,
                'cpf' => '000.000.000-00', // ✅ ATENÇÃO: Placeholder! Você precisará coletar o CPF no formulário
                'status' => 'Inscrição Incompleta',
                // Outros campos obrigatórios de Candidato se houver
            ]);
        }

        event(new Registered($user)); // Dispara evento para enviar e-mail de verificação

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Carrega os papéis do usuário para exibição
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all(); // Obtém todos os papéis para o formulário de edição
        $user->load('roles'); // Carrega os papéis atuais do usuário para marcar no formulário
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'role' => in_array('admin', $request->input('roles', [])) ? 'admin' : 'candidato', // Manter compatibilidade
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Sincronizar papéis Spatie: remove os antigos e anexa os novos
        $user->syncRoles($request->input('roles', []));

        // Se o usuário tiver o papel 'estagiario', cria um registro na tabela 'candidatos' se ainda não existir
        if ($user->hasRole('estagiario') && !$user->candidato) {
            Candidato::create([
                'user_id' => $user->id,
                'nome_completo' => $user->name,
                'cpf' => '000.000.000-00', // ✅ ATENÇÃO: Placeholder!
                'status' => 'Inscrição Incompleta',
            ]);
        } 
        // Se o usuário deixar de ser 'estagiario' e tiver um perfil de Candidato, podemos desativá-lo ou pensar em outra lógica
        // else if (!$user->hasRole('estagiario') && $user->candidato) {
        //     // Opcional: Desativar ou desvincular o perfil Candidato
        // }


        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Impede que o próprio admin logado se exclua
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Você não pode excluir sua própria conta de administrador.');
        }

        try {
            // Se o usuário tiver um perfil de Candidato, exclua-o primeiro
            if ($user->candidato) {
                $user->candidato->delete();
            }
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao excluir usuário ID {$user->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao excluir o usuário.');
        }
    }

    /**
     * Reenvia o e-mail de verificação para o usuário.
     */
    public function resendVerificationEmail(Request $request, User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', 'O e-mail deste usuário já está verificado.');
        }

        $user->sendEmailVerificationNotification();

        return redirect()->back()->with('success', 'E-mail de verificação reenviado para ' . $user->email . '.');
    }
}
