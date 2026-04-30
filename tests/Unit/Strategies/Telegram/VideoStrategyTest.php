<?php

namespace Tests\Unit\Strategies\Telegram;

use Tests\TestCase;
use App\Strategies\Telegram\VideoStrategy;
use App\Services\TelegramService;
use Mockery;
use Mockery\MockInterface;

/**
 * Class VideoStrategyTest
 * 
 * Verifies that the VideoStrategy correctly acknowledges receipt of video
 * messages from users.
 */
class VideoStrategyTest extends TestCase
{
    /** @var TelegramService|MockInterface */
    protected $telegramMock;
    
    protected $strategy;

    /**
     * Set up the strategy and its dependencies using Mockery.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->telegramMock = Mockery::mock(TelegramService::class);
        $this->strategy = new VideoStrategy($this->telegramMock);
    }

    /**
     * Test if the strategy correctly acknowledges a video message.
     */
    public function test_it_handles_video_message()
    {
        $message = [
            'chat' => ['id' => 123],
            'video' => ['file_id' => 'vid123']
        ];

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with(123, Mockery::type('string'))
            ->andReturn(['ok' => true]);

        $this->strategy->handle($message);
    }
}
