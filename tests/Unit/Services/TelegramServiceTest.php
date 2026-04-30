<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Http;

/**
 * Class TelegramServiceTest
 * 
 * Tests the TelegramService to ensure it can successfully send various types
 * of messages (text, location, video) to the Telegram Bot API.
 */
class TelegramServiceTest extends TestCase
{
    protected TelegramService $telegramService;

    /**
     * Set up the test environment by instantiating the TelegramService.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->telegramService = new TelegramService();
    }

    /**
     * Test if the service can correctly send a text message via Telegram API.
     */
    public function test_it_can_send_message()
    {
        Http::fake([
            'api.telegram.org/bot*/sendMessage' => Http::response(['ok' => true], 200)
        ]);

        $response = $this->telegramService->sendMessage('123456', 'Hello World');

        $this->assertTrue($response['ok']);
        
        Http::assertSent(function ($request) {
            return $request['chat_id'] === '123456' &&
                   $request['text'] === 'Hello World';
        });
    }

    /**
     * Test if the service can correctly send a location via Telegram API.
     */
    public function test_it_can_send_location()
    {
        Http::fake([
            'api.telegram.org/bot*/sendLocation' => Http::response(['ok' => true], 200)
        ]);

        $response = $this->telegramService->sendLocation('123456', -6.200000, 106.816666);

        $this->assertTrue($response['ok']);
        
        Http::assertSent(function ($request) {
            return $request['chat_id'] === '123456' &&
                   $request['latitude'] === -6.200000 &&
                   $request['longitude'] === 106.816666;
        });
    }

    /**
     * Test if the service can correctly send a video message via Telegram API.
     */
    public function test_it_can_send_video()
    {
        Http::fake([
            'api.telegram.org/bot*/sendVideo' => Http::response(['ok' => true], 200)
        ]);

        $response = $this->telegramService->sendVideo('123456', 'https://example.com/video.mp4', 'Check this out');

        $this->assertTrue($response['ok']);
        
        Http::assertSent(function ($request) {
            return $request['chat_id'] === '123456' &&
                   $request['video'] === 'https://example.com/video.mp4' &&
                   $request['caption'] === 'Check this out';
        });
    }
}
