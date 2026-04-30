<?php

namespace Tests\Unit\Strategies\Telegram;

use Tests\TestCase;
use App\Strategies\Telegram\TextStrategy;
use App\Services\TelegramService;
use App\Services\ZomatoService;
use Mockery;
use Mockery\MockInterface;

/**
 * Class TextStrategyTest
 * 
 * Verifies that the TextStrategy correctly handles Telegram text messages,
 * including bot commands like /start and restaurant search queries via Zomato.
 */
class TextStrategyTest extends TestCase
{
    /** @var TelegramService|MockInterface */
    protected $telegramMock;
    
    /** @var ZomatoService|MockInterface */
    protected $zomatoMock;
    
    protected TextStrategy $strategy;

    /**
     * Set up the strategy and its dependencies using Mockery.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->telegramMock = Mockery::mock(TelegramService::class);
        $this->zomatoMock = Mockery::mock(ZomatoService::class);
        $this->strategy = new TextStrategy($this->telegramMock, $this->zomatoMock);
    }

    /**
     * Test if the strategy correctly handles the /start command.
     */
    public function test_it_handles_start_command()
    {
        $message = [
            'chat' => ['id' => 123],
            'text' => '/start'
        ];

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with(123, Mockery::type('string'))
            ->andReturn(['ok' => true]);

        $this->strategy->handle($message);
    }

    /**
     * Test if the strategy correctly searches Zomato when a food name is sent.
     */
    public function test_it_searches_zomato_and_returns_results()
    {
        $message = [
            'chat' => ['id' => 123],
            'text' => 'Pizza'
        ];

        $this->zomatoMock->shouldReceive('search')
            ->once()
            ->with('Pizza')
            ->andReturn([
                'restaurants' => [
                    [
                        'restaurant' => [
                            'name' => 'Pizza Hut',
                            'location' => ['address' => 'Main St'],
                            'user_rating' => ['aggregate_rating' => 4.5],
                            'url' => 'http://example.com'
                        ]
                    ]
                ]
            ]);

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with(123, Mockery::on(function($text) {
                return str_contains($text, 'Pizza Hut');
            }))
            ->andReturn(['ok' => true]);

        $this->strategy->handle($message);
    }

    /**
     * Test if the strategy correctly handles cases where no search results are found.
     */
    public function test_it_handles_empty_search_results()
    {
        $message = [
            'chat' => ['id' => 123],
            'text' => 'UnknownFood'
        ];

        $this->zomatoMock->shouldReceive('search')
            ->once()
            ->with('UnknownFood')
            ->andReturn(['restaurants' => []]);

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with(123, "Sorry, no restaurants found for 'UnknownFood'.")
            ->andReturn(['ok' => true]);

        $this->strategy->handle($message);
    }
}
