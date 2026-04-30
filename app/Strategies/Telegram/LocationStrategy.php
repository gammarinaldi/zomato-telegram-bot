<?php

namespace App\Strategies\Telegram;

use App\Services\TelegramService;
use App\Services\ZomatoService;

class LocationStrategy implements TelegramMessageStrategy
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
        $lat = $message['location']['latitude'];
        $lon = $message['location']['longitude'];

        $results = $this->zomato->search('', $lat, $lon);

        if (empty($results['restaurants'])) {
            return $this->telegram->sendMessage($chatId, "Sorry, no restaurants found nearby.");
        }

        $response = "Found these restaurants near you:\n\n";
        foreach (array_slice($results['restaurants'], 0, 5) as $item) {
            $r = $item['restaurant'];
            $response .= "*{$r['name']}*\n";
            $response .= "📍 {$r['location']['address']}\n\n";
        }

        return $this->telegram->sendMessage($chatId, $response);
    }
}
