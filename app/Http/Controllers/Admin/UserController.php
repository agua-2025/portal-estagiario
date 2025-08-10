<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');

        $query = User::query()->with('roles');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter && $roleFilter !== 'all') {
            $query->role($roleFilter);
        }

        $users = $query->orderBy('name')->paginate(15);
        
        // Apenas admin e candidato
        $roles = ['admin', 'candidato'];
        
        return view('admin.users.index', compact('users', 'search', 'roleFilter', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Apenas admin e candidato
        $roles = ['admin', 'candidato'];
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['array', 'required'],
            'roles.*' => ['string', 'in:admin,candidato'], // Apenas admin ou candidato
            'cpf' => [
                'nullable',
                'string',
                'max:14',
                Rule::when(in_array('candidato', $request->roles), ['required', 'unique:candidatos,cpf'])
            ],
        ], [
            'cpf.required' => 'O CPF é obrigatório para usuários com papel Candidato.',
            'cpf.unique' => 'Este CPF já está cadastrado para outro candidato.',
            'roles.required' => 'Selecione pelo menos um papel para o usuário.',
            'roles.*.in' => 'Papel inválido selecionado.',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'role' => in_array('admin', $validatedData['roles']) ? 'admin' : 'candidato',
                ]);

                $user->assignRole($validatedData['roles']);

                // Criar registro de candidato apenas para papel 'candidato'
                if ($user->hasRole('candidato')) {
                    $this->createCandidatoProfile($user, $validatedData);
                }
                
                event(new Registered($user));
            });

            return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ocorreu um erro ao criar o usuário: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('candidato');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Edite seu próprio perfil na tela de configurações de conta.');
        }

        $roles = ['admin', 'candidato']; // Apenas admin e candidato
        $userRoles = $user->roles->pluck('name')->toArray();
        $user->load('candidato');
        
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'roles' => ['array', 'required'],
            'roles.*' => ['string', 'in:admin,candidato'], // Apenas admin ou candidato
        ];

        $isBecomingCandidato = in_array('candidato', $request->input('roles', []));
        $hasNoCandidatoProfile = !$user->candidato;

        // CPF é obrigatório apenas para candidatos
        if ($isBecomingCandidato && $hasNoCandidatoProfile) {
            $rules['cpf'] = ['required', 'string', 'max:14', Rule::unique('candidatos', 'cpf')];
        } elseif ($user->candidato) {
            $rules['cpf'] = ['nullable', 'string', 'max:14', Rule::unique('candidatos', 'cpf')->ignore($user->candidato->id)];
        } else {
            $rules['cpf'] = ['nullable', 'string', 'max:14'];
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $validatedData = $request->validate($rules, [
            'cpf.required' => 'O CPF é obrigatório para usuários com papel Candidato.',
            'cpf.unique' => 'Este CPF já está cadastrado para outro candidato.',
            'roles.required' => 'Selecione pelo menos um papel para o usuário.',
            'roles.*.in' => 'Papel inválido selecionado.',
        ]);

        try {
            DB::transaction(function () use ($validatedData, $user, $request) {
                $user->fill([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'role' => in_array('admin', $validatedData['roles']) ? 'admin' : 'candidato',
                ]);

                if ($request->filled('password')) {
                    $user->password = Hash::make($validatedData['password']);
                }

                $user->save();
                $user->syncRoles($validatedData['roles'] ?? []);

                // Gerenciar perfil de candidato
                if ($user->hasRole('candidato')) {
                    $this->updateCandidatoProfile($user, $validatedData);
                } else {
                    // Se não é mais candidato, remover perfil de candidato
                    if ($user->candidato) {
                        $user->candidato->delete();
                    }
                }
            });

            return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ocorreu um erro ao atualizar o usuário: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Você não pode apagar sua própria conta de administrador.');
        }

        if ($user->hasRole('admin')) {
            $adminCount = User::role('admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Não é possível apagar o único administrador do sistema. Nomeie outro administrador primeiro.');
            }
        }

        try {
            DB::transaction(function () use ($user) {
                if ($user->candidato) {
                    $user->candidato->delete();
                }
                $user->delete();
            });

            return redirect()->route('admin.users.index')->with('success', 'Usuário apagado com sucesso!');
        } catch (\Exception $e) {
            \Log::error("Erro ao apagar usuário ID {$user->id}: " . $e->getMessage(), [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Ocorreu um erro ao apagar o usuário.');
        }
    }

    /**
     * Reenvia o e-mail de verificação para o usuário.
     */
    public function resendVerificationEmail(Request $request, User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return back()->with('warning', 'O e-mail deste usuário já está verificado.');
        }
        
        $user->sendEmailVerificationNotification();
        return back()->with('success', 'E-mail de verificação reenviado para ' . $user->email . '.');
    }

    /**
     * Cria o perfil de candidato
     */
    private function createCandidatoProfile(User $user, array $validatedData)
    {
        Candidato::create([
            'user_id' => $user->id,
            'nome_completo' => $validatedData['name'],
            'cpf' => $validatedData['cpf'],
            'status' => 'Inscrição Incompleta',
        ]);
    }

    /**
     * Atualiza o perfil de candidato
     */
    private function updateCandidatoProfile(User $user, array $validatedData)
    {
        if (!$user->candidato) {
            $this->createCandidatoProfile($user, $validatedData);
        } else {
            $user->candidato->fill([
                'nome_completo' => $user->name,
                'cpf' => $validatedData['cpf'] ?? $user->candidato->cpf,
            ])->save();
        }
    }
}