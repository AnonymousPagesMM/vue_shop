<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SentOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $otp;

    public function __construct($email,$otp)
    {
        $this->email = $email;
        $this->otp = $otp;
    }
    public function envelope()
    {
        return new Envelope(
            subject: 'Sent Otp Is This',
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.sentotp',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
    }
}
