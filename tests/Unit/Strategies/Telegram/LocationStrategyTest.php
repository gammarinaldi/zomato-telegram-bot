<?php

namespace Tests\Unit\Strategies\Telegram;

use Tests\TestCase;
use App\Strategies\Telegram\LocationStrategy;
use App\Services\TelegramService;
use App\Services\ZomatoService;
use Mockery;
use Mockery\MockInterface;

/**
 * Class LocationStrategyTest
 * 
 * Verifies that the LocationStrategy correctly handles Telegram location messages
 * by searching for nearby restaurants using Zomato and responding via Telegram.
 */
class LocationStrategyTest extends TestCase
{
    /** @var TelegramService|MockInterface */
    protected $telegramMock;
    
    /** @var ZomatoService|MockInterface */
    protected $zomatoMock;
    
    protected LocationStrategy $strategy;

    /**
     * Set up the strategy and its dependencies using Mockery.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->telegramMock = Mockery::mock(TelegramService::class);
        $this->zomatoMock = Mockery::mock(ZomatoService::class);
        $this->strategy = new LocationStrategy($this->telegramMock, $this->zomatoMock);
    }

    /**
     * Test if the strategy correctly processes a location and suggests nearby restaurants.
     */
    public function test_it_handles_location_message()
    {
        $message = [
            'chat' => ['id' => 123],
            'location' => [
                'latitude' => -6.2,
                'longitude' => 106.8
            ]
        ];

        $this->zomatoMock->shouldReceive('search')
            ->once()
            ->with('', -6.2, 106.8)
            ->andReturn([
                'restaurants' => [
                    [
                        'restaurant' => [
                            'name' => 'Nearby Cafe',
                            'location' => ['address' => 'Corner St']
                        ]
                    ]
                ]
            ]);

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with(123, Mockery::on(function($text) {
                return str_contains($text, 'Nearby Cafe');
            }))
            ->andReturn(['ok' => true]);

        $this->strategy->handle($message);
    }

    /**
     * Test if the strategy correctly handles cases where no restaurants are found nearby.
     */
    public function test_it_handles_no_restaurants_nearby()
    {
        $message = [
            'chat' => ['id' => 123],
            'location' => [
                'latitude' => 0,
                'longitude' => 0
            ]
        ];

        $this->zomatoMock->shouldReceive('search')
            ->once()
            ->with('', 0, 0)
            ->andReturn(['restaurants' => []]);

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with(123, "Sorry, no restaurants found nearby.")
            ->andReturn(['ok' => true]);

        $this->strategy->handle($message);
    }
}
