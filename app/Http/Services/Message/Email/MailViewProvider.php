<?php

namespace App\Http\Services\Message\Email;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailViewProvider extends Mailable
{
    use Queueable, SerializesModels;

    public array $details;
    public $subject;
    public $from;

    public function __construct($details, $subject, array $from)
    {
        $this->details = $details;
        $this->subject = $subject;
        $this->from = $from;
    }

    public function build(): MailViewProvider
    {
        return $this->subject($this->subject)->view('emails.send-otp');
    }
}
