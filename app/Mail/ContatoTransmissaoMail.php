<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContatoTransmissaoMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public string $body;

    public function __construct(string $subjectLine, string $body)
    {
        $this->subjectLine = $subjectLine;
        $this->body = $body;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->subjectLine)
            ->view('emails.transmissao')       // HTML
            ->text('emails.transmissao_plain'); // Texto puro (fallback)
    }
}
