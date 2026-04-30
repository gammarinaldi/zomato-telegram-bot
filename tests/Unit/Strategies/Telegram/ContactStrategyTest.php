<?php

namespace Tests\Unit\Strategies\Telegram;

use Tests\TestCase;
use App\Strategies\Telegram\ContactStrategy;
use App\Services\TelegramService;
use Mockery;
use Mockery\MockInterface;

/**
 * Class ContactStrategyTest
 * 
 * Verifies that the ContactStrategy correctly processes Telegram contact messages
 * and sends a confirmation response to the user.
 */
class ContactStrategyTest extends TestCase
{
    /** @var TelegramService|MockInterface */
    protected $telegramMock;
    
    protected ContactStrategy $strategy;

    /**
     * Set up the strategy and its dependencies using Mockery.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->telegramMock = Mockery::mock(TelegramService::class);
        $this->strategy = new ContactStrategy($this->telegramMock);
    }

    /**
     * Test if the strategy correctly parses and acknowledges a contact message.
     */
    public function test_it_handles_contact_message()
    {
        $message = [
            'chat' => ['id' => 123],
            'contact' => [
                'first_name' => 'John',
                'phone_number' => '+628123456789'
            ]
        ];

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with(123, Mockery::on(function($text) {
                return str_contains($text, 'John') && str_contains($text, '+628123456789');
            }))
            ->andReturn(['ok' => true]);

        $this->strategy->handle($message);
    }
}
