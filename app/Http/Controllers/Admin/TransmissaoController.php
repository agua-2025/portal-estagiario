<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContatoTransmissaoMail;

class TransmissaoController extends Controller
{
    public function index(Request $req)
    {
        $q = trim((string) $req->query('q'));
        $status = $req->query('status'); // opcional: em_analise, incompletos, etc.

        $cands = Candidato::with(['curso','user'])
            ->when($q, function ($query) use ($q) {
                $like = "%{$q}%";
                $query->where(function ($w) use ($like) {
                    $w->where('nome_completo', 'like', $like)
                      ->orWhere('cpf', 'like', $like)
                      ->orWhere('telefone', 'like', $like)
                      ->orWhereHas('user', fn($u) => $u->where('email', 'like', $like));
                });
            })
            ->when($status === 'incompletos', fn($q) => $q->where('status', 'Inscrição Incompleta'))
            ->when($status === 'em_analise',  fn($q) => $q->where('status', 'Em Análise'))
            ->when($status === 'aprovado',    fn($q) => $q->where('status', 'Aprovado'))
            ->when($status === 'homologado',  fn($q) => $q->where('status', 'Homologado'))
            ->when($status === 'convocado',   fn($q) => $q->where('status', 'Convocado'))
            ->orderBy('nome_completo')
            ->paginate(25)
            ->appends($req->query());

        $templates = config('transmissao.templates', []);

        return view('admin.transmissao.index', compact('cands','templates','q','status'));
    }

    public function whatsapp(Request $req, Candidato $candidato)
    {
        // texto pode vir por query (?text=) ou por input (hidden do form)
        $raw = (string) $req->input('text', $req->query('text', ''));
        $tplDefault = config('transmissao.templates.incompleto', '');
        $msg = $this->fillTemplate($raw ?: $tplDefault, $candidato);

        $phone = $this->e164($candidato->telefone); // ex: 5565999999999
        if (!$phone) {
            return back()->with('error', 'Candidato sem telefone válido para WhatsApp.');
        }

        $url = 'https://wa.me/' . rawurlencode($phone) . '?text=' . rawurlencode($msg);

        // marca contato
        $candidato->forceFill([
            'last_contacted_at' => now(),
            'last_contact_via'  => 'whatsapp',
        ])->save();

        // Obs.: abrir em nova aba é função da VIEW (use target="_blank" no <a>).
        return redirect()->away($url);
    }

    public function email(Request $req, Candidato $candidato)
    {
        $to = optional($candidato->user)->email;
        if (!$to) {
            return back()->with('error', 'Candidato sem e-mail cadastrado.');
        }

        $subject   = trim((string) $req->input('subject', 'Portal do Estagiário'));
        $raw       = (string) $req->input('text', '');
        $tplDefault= config('transmissao.templates.incompleto', '');
        $body      = $this->fillTemplate($raw ?: $tplDefault, $candidato);

        try {
            Mail::to($to)->send(new ContatoTransmissaoMail($subject, $body));
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Falha ao enviar e-mail: '.$e->getMessage());
        }

        $candidato->forceFill([
            'last_contacted_at' => now(),
            'last_contact_via'  => 'email',
        ])->save();

        return back()->with('success', 'E-mail enviado e contato registrado.');
    }

    private function fillTemplate(string $tpl, Candidato $c): string
    {
        $primeiro = trim(explode(' ', (string)$c->nome_completo)[0] ?? '');
        if ($primeiro !== '') {
            $primeiro = Str::title(Str::lower($primeiro));
        }

        $map = [
            '{nome}'          => $c->nome_completo ?? '',
            '{primeiro_nome}' => $primeiro,
            '{percent}'       => $c->percentual_completo ?? '0',
            '{curso}'         => optional($c->curso)->nome ?? '—',
            '{status}'        => $c->status ?? '',
            '{link_login}'    => route('login'),
        ];

        return strtr($tpl, $map);
    }

    private function e164(?string $telefone): ?string
    {
        // Brasil: remove tudo que não é dígito e prefixa 55 se faltar
        $d = preg_replace('/\D+/', '', (string) $telefone);
        if (!$d) return null;
        if (!Str::startsWith($d, '55')) {
            $d = '55' . $d;
        }
        // sanity check simples (BR costuma ficar 12–13 dígitos com DDI+DDD+9)
        if (strlen($d) < 12) return null;

        return $d;
        }
}
