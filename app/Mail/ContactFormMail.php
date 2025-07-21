<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address; // Importa a classe Address
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $subject;
    public $messageContent;

    /**
     * Cria uma nova instância da mensagem.
     *
     * @param string $name
     * @param string $email
     * @param string $subject
     * @param string $messageContent
     * @return void
     */
    public function __construct($name, $email, $subject, $messageContent)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->messageContent = $messageContent;
    }

    /**
     * Obtém o envelope da mensagem.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            // O e-mail SERÁ SEMPRE ENVIADO DO SEU MAIL_FROM_ADDRESS (contato@portaldoestagiario.com)
            // O e-mail do usuário do formulário será adicionado ao 'Reply-To'
            from: new Address(config('mail.from.address'), config('mail.from.name')), // Remetente real (seu e-mail do Titan)
            replyTo: [
                new Address($this->email, $this->name), // O e-mail do usuário para você responder
            ],
            subject: 'Contato do Site: ' . $this->subject, // Assunto do e-mail que você receberá
        );
    }

    /**
     * Obtém a definição do conteúdo da mensagem.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.contact-form', // A view Blade que será usada para o corpo do e-mail
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'messageContent' => $this->messageContent,
            ],
        );
    }

    /**
     * Obtém os anexos para a mensagem.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}