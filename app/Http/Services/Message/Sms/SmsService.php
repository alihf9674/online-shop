<?php

namespace App\Http\Services\Message\Sms;

use App\Http\Interfaces\MessageInterface;

class SmsService implements MessageInterface
{
    private string $from;
    private string $text;
    private array $to;
    private bool $isFlash = true;

    public function fire():void
    {
        $meliPayamak = new MeliPayamakService();
        $meliPayamak->sendSmsSoapClient($this->from, $this->to,$this->text,$this->isFlash);
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getTo(): array
    {
        return $this->to;
    }

    public function setTo($to)
    {
        $this->to = $to;
    }

    public function getIsFlash(): bool
    {
        return $this->isFlash;
    }

    public function setIsFlash($to)
    {
        $this->isFlash = $to;
    }
}
