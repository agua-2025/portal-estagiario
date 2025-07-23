<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mostra o painel de controlo do administrador com estatísticas e ações pendentes.
     */
    public function index()
    {
        // 1. Obter as estatísticas
        $totalInscricoes = Candidato::count();
        $aguardandoAnalise = Candidato::where('status', 'Aguardando Análise')->count();
        $aprovados = Candidato::where('status', 'Aprovado')->count();
        $rejeitados = Candidato::where('status', 'Rejeitado')->count();

        // 2. Obter as últimas 10 inscrições que precisam de ser analisadas
        $ultimasPendentes = Candidato::where('status', 'Aguardando Análise')
                                      ->with('user', 'curso') // Otimização para carregar dados relacionados
                                      ->latest() // Ordena pelas mais recentes
                                      ->take(10)
                                      ->get();

        // 3. Enviar os dados para a view
        return view('admin.dashboard', compact(
            'totalInscricoes',
            'aguardandoAnalise',
            'aprovados',
            'rejeitados',
            'ultimasPendentes'
        ));
    }
}