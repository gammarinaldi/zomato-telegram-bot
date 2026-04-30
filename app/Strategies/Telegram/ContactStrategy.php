<?php

namespace App\Strategies\Telegram;

use App\Services\TelegramService;

class ContactStrategy implements TelegramMessageStrategy
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function handle(array $message)
    {
        $chatId = $message['chat']['id'];
        $contact = $message['contact'];
        
        $firstName = $contact['first_name'] ?? '';
        $phoneNumber = $contact['phone_number'] ?? '';

        $text = "Thank you, {$firstName}! We have received your contact info ({$phoneNumber}). We will notify you about your restaurant bookings.";
        
        $this->telegram->sendMessage($chatId, $text);
    }
}
