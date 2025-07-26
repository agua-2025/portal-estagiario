<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidato;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered; // Importa o evento Registered para envio de email

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
            'roles' => 'array',
            'roles.*' => ['string', Rule::exists('roles', 'name')],
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

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                // Remove 'email_verified_at' aqui para que o usuário precise verificar
            ]);

            // Dispara o evento Registered para enviar o e-mail de verificação
            event(new Registered($user));

            if (isset($validatedData['roles'])) {
                $user->syncRoles($validatedData['roles']);
            } else {
                $user->assignRole('estagiario');
            }

            if ($user->hasRole('estagiario')) {
                // Se o usuário criado tiver o papel 'estagiario', criar um registro Candidato associado
                // ATENÇÃO: O CPF aqui é um placeholder. Você precisará coletá-lo no formulário
                // ou gerá-lo de forma única, pois ele é NOT NULL e UNIQUE na tabela `candidatos`.
                $candidato = Candidato::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nome_completo' => $user->name,
                        'cpf' => '000.000.000-00', 
                        'status' => 'Inscrição Incompleta',
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso! Um e-mail de verificação foi enviado.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Erro ao criar usuário: " . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao criar o usuário.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
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
        if (auth()->user()->id === $user->id) {
            return redirect()->back()->with('error', 'Você não pode excluir sua própria conta de administrador.');
        }

        DB::beginTransaction();
        try {
            if ($user->candidato) {
                $user->candidato->delete();
            }

            $user->delete();
            
            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Usuário e dados associados (se houver) apagados com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Erro ao apagar usuário ID {$user->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao apagar o usuário.');
        }
    }

    /**
     * Reenvia o e-mail de verificação para o usuário.
     */
    public function resendVerificationEmail(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', 'O e-mail deste usuário já está verificado.');
        }

        $user->sendEmailVerificationNotification();

        return redirect()->back()->with('success', 'E-mail de verificação reenviado com sucesso para ' . $user->email . '.');
    }
}