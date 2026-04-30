<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected $baseUrl;
    protected $botToken;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
        $this->baseUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    /**
     * Send a text message.
     */
    public function sendMessage($chatId, $text, $options = [])
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
        ], $options);

        return $this->request('POST', '/sendMessage', $params);
    }

    /**
     * Send a location.
     */
    public function sendLocation($chatId, $latitude, $longitude)
    {
        return $this->request('POST', '/sendLocation', [
            'chat_id' => $chatId,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    /**
     * Send a video.
     */
    public function sendVideo($chatId, $videoUrl, $caption = '')
    {
        return $this->request('POST', '/sendVideo', [
            'chat_id' => $chatId,
            'video' => $videoUrl,
            'caption' => $caption,
        ]);
    }

    /**
     * Helper to make API requests.
     */
    protected function request($method, $endpoint, $params = [])
    {
        $response = Http::post($this->baseUrl . $endpoint, $params);
        return $response->json();
    }
}
