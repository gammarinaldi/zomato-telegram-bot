<?php

namespace App\Strategies\Telegram;

use App\Services\TelegramService;
use App\Services\ZomatoService;

class TextStrategy implements TelegramMessageStrategy
{
    protected $telegram;
    protected $zomato;

    public function __construct(TelegramService $telegram, ZomatoService $zomato)
    {
        $this->telegram = $telegram;
        $this->zomato = $zomato;
    }

    public function handle(array $message)
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        if (strtolower($text) === '/start') {
            return $this->telegram->sendMessage($chatId, "Welcome to Zomato Bot! Send me a restaurant name or keyword to search.");
        }

        $results = $this->zomato->search($text);
        
        if (empty($results['restaurants'])) {
            return $this->telegram->sendMessage($chatId, "Sorry, no restaurants found for '{$text}'.");
        }

        $response = "Found these restaurants:\n\n";
        foreach (array_slice($results['restaurants'], 0, 5) as $item) {
            $r = $item['restaurant'];
            $response .= "*{$r['name']}*\n";
            $response .= "📍 {$r['location']['address']}\n";
            $response .= "⭐ Rating: {$r['user_rating']['aggregate_rating']}\n";
            $response .= "🔗 [View More]({$r['url']})\n\n";
        }

        return $this->telegram->sendMessage($chatId, $response);
    }
}
