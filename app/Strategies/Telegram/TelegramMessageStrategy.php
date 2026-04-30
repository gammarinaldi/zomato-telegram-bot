<?php

namespace App\Strategies\Telegram;

interface TelegramMessageStrategy
{
    public function handle(array $message);
}
