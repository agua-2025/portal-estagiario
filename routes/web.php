<?php

use Illuminate\Support\Facades\Route;
use App\Models\Estado;
use Illuminate\Support\Facades\Auth;
use App\Models\Page;

// ✅ Importações de Controllers
use App\Http\Controllers\WelcomeController; // Controlador da página inicial pública
use App\Http\Controllers\CursoController; // Controlador público para detalhes do curso
use App\Http\Controllers\ContactController;

// Controllers Genéricos e de Candidato
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Candidato\ProfileController as CandidatoProfileController;
use App\Http\Controllers\Candidato\DocumentoController;
use App\Http\Controllers\Candidato\AtividadeController; 
use App\Http\Controllers\Candidato\RecursoController; // Controller do Recurso
use App\Http\Controllers\ClassificacaoController; // Controlador da classificação pública - CORRIGIDO AQUI!

// Controllers do Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InstituicaoController;
use App\Http\Controllers\Admin\CursoController as AdminCursoController; 
use App\Http\Controllers\Admin\TipoDeAtividadeController; // CORRIGIDO AQUI!
use App\Http\Controllers\Admin\CandidatoController; // CORRIGIDO AQUI!
use App\Http\Controllers\Admin\AtividadeAnaliseController; // CORRIGIDO AQUI!
use App\Http\Controllers\Admin\PageController; // CORRIGIDO AQUI!
use App\Http\Controllers\Admin\UserController; // Importação do UserController


/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// Rota da página inicial
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/classificacao', [ClassificacaoController::class, 'index'])->name('classificacao.index');

// Rotas para páginas dinâmicas (Política de Privacidade, Termos de Uso, Sobre Nós)
Route::get('/politica-privacidade', function () {
    $page = Page::where('slug', 'politica-de-privacidade')->firstOrFail();
    return view('public.politica-privacidade', compact('page'));
})->name('politica-privacidade');

Route::get('/termos-de-uso', function () {
    $page = Page::where('slug', 'termos-de-uso')->firstOrFail();
    return view('public.termos-de-uso', compact('page'));
})->name('termos-de-uso');

Route::get('/sobre-nos', function () {
    $page = Page::where('slug', 'sobre-nos')->firstOrFail();
    return view('public.sobre-nos', compact('page'));
})->name('sobre-nos');


// Rota para exibir detalhes de um curso específico
Route::get('/cursos/{curso}', [CursoController::class, 'show'])->name('cursos.show');

// Rota de Classificacao pública completa
Route::get('/contato', [ContactController::class, 'showForm'])->name('contato.show');
Route::post('/contato', [ContactController::class, 'sendEmail'])->name('contato.send');

/*
|--------------------------------------------------------------------------
| Rota de API (para o JavaScript buscar as cidades)
|--------------------------------------------------------------------------
*/
Route::get('/api/cidades/{estado}', function (Estado $estado) {
    return $estado->cidades()->orderBy('nome')->get();
});


/*
|--------------------------------------------------------------------------
| Rotas de Usuário Logado (Candidato ou Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Rota de dashboard inteligente
    Route::get('/dashboard', function () {
        // Usa hasRole do Spatie para verificar o papel
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        // Aponta para o painel do candidato
        return app(\App\Http\Controllers\Candidato\DashboardController::class)->index();
    })->name('dashboard');
    
    // Rota de perfil padrão do Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // CORRIGIDO AQUI!

    // Rotas do Perfil do Candidato
    Route::get('/meu-perfil', [CandidatoProfileController::class, 'edit'])->name('candidato.profile.edit');
    Route::put('/meu-perfil', [CandidatoProfileController::class, 'update'])->name('candidato.profile.update');

    // Rotas de Documentos do Candidato
    Route::get('/meus-documentos', [DocumentoController::class, 'index'])->name('candidato.documentos.index'); 
    Route::post('/meus-documentos', [DocumentoController::class, 'store'])->name('candidato.documentos.store');
    Route::get('/documentos/{documento}', [DocumentoController::class, 'show'])->name('candidato.documentos.show');
    
    // Rotas de Atividades do Candidato
    Route::delete('/candidato/atividades/{atividade}', [AtividadeController::class, 'destroy'])->name('candidato.atividades.destroy'); 
    Route::get('/minhas-atividades', [AtividadeController::class, 'index'])->name('candidato.atividades.index'); 
    Route::post('/minhas-atividades', [AtividadeController::class, 'store'])->name('candidato.atividades.store');
    Route::get('/candidato/atividades/{atividade}/edit', [AtividadeController::class, 'edit'])->name('candidato.atividades.edit');
    Route::put('/candidato/atividades/{atividade}', [AtividadeController::class, 'update'])->name('candidato.atividades.update');
    Route::get('/atividades/{atividade}/visualizar', [AtividadeController::class, 'show'])->name('candidato.atividades.show');

    // Rotas de Recurso do Candidato
    Route::get('/meu-recurso', [RecursoController::class, 'create'])->name('candidato.recurso.create');
    Route::post('/meu-recurso', [RecursoController::class, 'store'])->name('candidato.recurso.store');
});


/*
|--------------------------------------------------------------------------
| Rotas do Painel Administrativo
|--------------------------------------------------------------------------
*/
// CORREÇÃO: Removido 'role:admin' e usando verificação manual
Route::middleware(['auth', 'verified']) 
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('instituicoes', InstituicaoController::class);
        Route::resource('cursos', AdminCursoController::class); // CORRIGIDO AQUI!
        Route::resource('tipos-de-atividade', TipoDeAtividadeController::class); // CORRIGIDO AQUI!
        Route::resource('candidatos', CandidatoController::class);
        Route::resource('pages', PageController::class);
        Route::get('ranking-convocacao', [CandidatoController::class, 'ranking'])->name('candidatos.ranking');
        Route::get('candidatos/{candidato}/atribuir-vaga', [CandidatoController::class, 'showAtribuirVagaForm'])->name('candidatos.showAtribuirVagaForm');
        Route::post('candidatos/{candidato}/convocar', [CandidatoController::class, 'convocar'])->name('candidatos.convocar');


        // Rotas para Gerenciamento de Usuários (com o novo UserController)
        Route::resource('users', UserController::class); // Importado no topo
       // Rota para reenviar email de verificação
        Route::post('users/{user}/resend-verification', [UserController::class, 'resendVerificationEmail'])
            ->name('users.resend-verification');

        Route::post('/atividades/{atividade}/aprovar', [AtividadeAnaliseController::class, 'aprovar'])->name('atividades.aprovar');
        Route::post('/atividades/{atividade}/rejeitar', [AtividadeAnaliseController::class, 'rejeitar'])->name('atividades.rejeitar');

        // Rota de atualização de status de documentos (para o admin)
        Route::put('/documentos/{documento}/status', [CandidatoController::class, 'updateDocumentStatus'])->name('documentos.updateStatus');
        
        // NOVA ROTA PARA HOMOLOGAR O CANDIDATO
        Route::post('candidatos/{candidato}/homologar', [CandidatoController::class, 'homologar'])->name('candidatos.homologar');
        Route::get('ranking-convocacao', [CandidatoController::class, 'ranking'])->name('candidatos.ranking');
        Route::post('candidatos/{candidato}/convocar', [CandidatoController::class, 'convocar'])->name('candidatos.convocar');
        
        // Rotas para decisão do recurso
        Route::post('recursos/{candidato}/deferir/{recurso_index}', [CandidatoController::class, 'deferirRecurso'])->name('recursos.deferir');
        Route::post('recursos/{candidato}/indeferir/{recurso_index}', [CandidatoController::class, 'indeferirRecurso'])->name('recursos.indeferir');
});

// ✅ ROTA DE TESTE - RESTAURADA
use App\Models\Candidato;
use Illuminate\Http\Request;

Route::get('/test-save', function () {
    // Tente encontrar o primeiro candidato que está "Em Análise"
    $candidato = Candidato::where('status', 'Em Análise')->first(); 

    // Se não encontrar, pegue qualquer candidato
    if (! $candidato) {
        $candidato = Candidato::first(); 
    }

    // Se ainda não houver nenhum candidato, exiba uma mensagem
    if (! $candidato) {
        return "Nenhum candidato encontrado no banco de dados para testar.";
    }

    // Cria um histórico de teste
    $test_reason = [
        [
            'timestamp' => now()->toDateTimeString(),
            'reason' => "Este é um teste de salvamento direto no banco.",
            'action' => 'test_save',
            'previous_status' => $candidato->status,
        ]
    ];

    // Tenta salvar o histórico
    try {
        $candidato->revert_reason = $test_reason;
        $candidato->save();

        // Recarrega o candidato do banco para ter certeza
        $candidato->refresh();

        echo "<h1>Teste Concluído para o Candidato: " . $candidato->nome_completo . "</h1>";
        echo "<h2>Conteúdo salvo no banco de dados:</h2>";
        echo "<pre>";
        print_r($candidato->revert_reason);
        echo "</pre>";

    } catch (\Exception $e) {
        echo "<h1>Ocorreu um erro ao tentar salvar!</h1>";
        echo "<p><strong>Mensagem do Erro:</strong> " . $e->getMessage() . "</p>";
    }
});
// FIM DA ROTA DE TESTE


// Inclui as rotas de autenticação (login, register, etc.) do Breeze
require __DIR__.'/auth.php';