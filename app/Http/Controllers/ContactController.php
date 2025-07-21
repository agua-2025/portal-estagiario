<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Importa a fachada Mail
use App\Mail\ContactFormMail; // Importa a classe Mailable que criaremos

class ContactController extends Controller
{
    /**
     * Exibe o formulário de contato.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('public.contato'); // Retorna a view do formulário de contato
    }

    /**
     * Processa o envio do formulário de contato e envia o e-mail.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendEmail(Request $request)
    {
        // 1. Validação dos dados do formulário
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // 2. Envia o e-mail usando a classe Mailable
        // O e-mail será enviado para o endereço definido em config/mail.php ou .env (MAIL_FROM_ADDRESS)
        // Você pode mudar o destinatário para um e-mail específico, ex: Mail::to('seu_email@dominio.com')->send(...)
        Mail::to(config('mail.from.address')) // Envia para o endereço de e-mail configurado no Laravel
            ->send(new ContactFormMail(
                $request->input('name'),
                $request->input('email'),
                $request->input('subject'),
                $request->input('message')
            ));

        // 3. Redireciona de volta com uma mensagem de sucesso
        return redirect()->route('contato.show')->with('success', 'Sua mensagem foi enviada com sucesso!');
    }
}