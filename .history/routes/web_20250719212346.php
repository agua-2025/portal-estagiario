<?php

use Illuminate\Support\Facades\Route;
use App\Models\Estado;
use Illuminate\Support\Facades\Auth;

// ✅ Importações de Controllers
use App\Http\Controllers\WelcomeController; // Controlador da página inicial pública
use App\Http\Controllers\CursoController; // Controlador público para detalhes do curso

// Controllers Genéricos e de Candidato
use App\Http\Controllers\ProfileController;
//use App\Http\Controllers\TesteController; // Mantenha comentado ou remova se não for usar
use App\Http\Controllers\Candidato\ProfileController as CandidatoProfileController;
use App\Http\Controllers\Candidato\DocumentoController;
use App\Http\Controllers\Candidato\AtividadeController; 
use App\Http\Controllers\ClassificacaoController; // Controlador da classificação pública

// Controllers do Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InstituicaoController;
use App\Http\Controllers\Admin\CursoController as AdminCursoController; 
use App\Http\Controllers\Admin\TipoDeAtividadeController;
use App\Http\Controllers\Admin\CandidatoController; 
use App\Http\Controllers\Admin\AtividadeAnaliseController; 


/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// Rota da página inicial
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Rota para exibir detalhes de um curso específico
Route::get('/cursos/{curso}', [CursoController::class, 'show'])->name('cursos.show');

// Rota de Classificação pública completa
Route::get('/classificacao', [ClassificacaoController::class, 'index'])->name('classificacao.index');

// Rota de Teste (mantida comentada conforme seu arquivo)
//Route::get('/teste', [TesteController::class, 'index']);

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
Route::middleware('auth')->group(function () {
    
    // Rota de dashboard inteligente
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        // Aponta para o painel do candidato
        return app(\App\Http\Controllers\Candidato\DashboardController::class)->index();
    })->name('dashboard');
    
    // Rota de perfil padrão do Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
});


/*
|--------------------------------------------------------------------------
| Rotas do Painel Administrativo
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\CheckAdminRole::class]) 
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('instituicoes', InstituicaoController::class);
        Route::resource('cursos', AdminCursoController::class); 
        Route::resource('tipos-de-atividade', TipoDeAtividadeController::class);
        Route::resource('candidatos', CandidatoController::class);
        
        Route::post('/atividades/{atividade}/aprovar', [AtividadeAnaliseController::class, 'aprovar'])->name('atividades.aprovar');
        Route::post('/atividades/{atividade}/rejeitar', [AtividadeAnaliseController::class, 'rejeitar'])->name('atividades.rejeitar');

        // Rota de atualização de status de documentos (para o admin)
        Route::put('/documentos/{documento}/status', [CandidatoController::class, 'updateDocumentStatus'])->name('documentos.updateStatus');
        
        // ✅ NOVA ROTA PARA HOMOLOGAR O CANDIDATO - ADICIONADA AQUI
        Route::post('candidatos/{candidato}/homologar', [CandidatoController::class, 'homologar'])->name('candidatos.homologar');
});

// ✅ INÍCIO DO CÓDIGO DE TESTE - COLE AQUI
use App\Models\Candidato;
use Illuminate\Http\Request;

Route::get('/test-save', function () {
    // Tente encontrar o primeiro candidato que está "Em Análise"
    $candidato = Candidato::where('status', 'Em Análise')->first();

    // Se não encontrar, pegue qualquer candidato
    if (!$candidato) {
        $candidato = Candidato::first();
    }

    // Se ainda não houver nenhum candidato, exiba uma mensagem
    if (!$candidato) {
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
// ✅ FIM DO CÓDIGO DE TESTE


// Inclui as rotas de autenticação (login, register, etc.) do Breeze
require __DIR__.'/auth.php';