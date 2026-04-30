<?php

namespace App\Strategies\Telegram;

use App\Services\TelegramService;

class VideoStrategy implements TelegramMessageStrategy
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function handle(array $message)
    {
        $chatId = $message['chat']['id'];
        return $this->telegram->sendMessage($chatId, "Thanks for the video! We'll use it to improve our restaurant reviews.");
    }
}
