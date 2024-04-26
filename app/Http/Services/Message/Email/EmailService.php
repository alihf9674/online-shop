<?php

namespace App\Http\Services\Message\Email;

use App\Http\Interfaces\MessageInterface;
use Illuminate\Support\Facades\Mail;

class EmailService implements MessageInterface
{
    private array $details;
    private string $subject;
    private array $to;
    private array $from = [
        ['address' => null, 'name' => null]
    ];

    public function fire():void
    {
        Mail::to($this->to)->send(new MailViewProvider($this->details, $this->subject, $this->from));
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails($details)
    {
        $this->details = $details;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getFrom(): array
    {
        return $this->from;
    }

    public function setFrom($address, $name)
    {
        $this->from = [
            [
                'address' => $address,
                'name' => $name,
            ]
        ];
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to)
    {
        $this->to = $to;
    }
}
