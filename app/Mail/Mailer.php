<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mailer extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): Mailer
    {
        return $this->from($address = 'noreply@domain.com', $name = 'Pins Generator Admin')
            ->subject('Pin Generator Service')
            ->view('emailsTemplates.generatedPinsEmail')
            ->with([
                'subject' => "Mail from Pins Generator",
                'data' => $this->data,
            ]);
    }
}
