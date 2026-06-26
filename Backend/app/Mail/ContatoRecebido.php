<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContatoRecebido extends Mailable
{
    use Queueable, SerializesModels;

    public $dados;
    
    /**
     * Create a new message instance.
     */
    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    public function build()
    {
        return $this->subject('Nova Mensagem de Contato - ConViva')
            ->html("
                <h2>Nova mensagem recebida de contato:</h2>
                <p><strong>Nome:</strong> {$this->dados['nome']}</p>
                <p><strong>Email:</strong> {$this->dados['email']}</p>
                <p><strong>Mensagem:</strong><br> {$this->dados['mensagem']}</p>
            ");
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contato Recebido',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
