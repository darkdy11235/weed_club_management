<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationPasswordCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $confirmationCode;

    public function __construct($confirmationCode)
    {
        $this->confirmationCode = $confirmationCode;
    }

    public function build()
    {
        return $this->view('emails.reset-password-code')
            ->subject('Confirmation Code');
    }
}
