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
    // Estatísticas
    $totalInscricoes   = Candidato::count();
    $aguardandoAnalise = Candidato::where('status', 'Em Análise')->count();
    $aprovados         = Candidato::where('status', 'Aprovado')->count();
    $rejeitados        = Candidato::where('status', 'Rejeitado')->count();
    $homologados       = Candidato::where('status', 'Homologado')->count();
    $incompletas       = Candidato::where('status', 'Inscrição Incompleta')->count(); 
    $convocados        = Candidato::where('status', 'Convocado')->count();            

    // Últimas pendentes
    $ultimasPendentes = Candidato::where('status', 'Em Análise')
        ->with('user','curso')
        ->latest()
        ->take(10)
        ->get();

    return view('admin.dashboard', compact(
        'totalInscricoes',
        'aguardandoAnalise',
        'aprovados',
        'rejeitados',
        'homologados',
        'incompletas',   // 👈 novo
        'convocados',    // 👈 novo
        'ultimasPendentes'
    ));
}
}