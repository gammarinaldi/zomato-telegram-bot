<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Http\Middleware\LogApiRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ApiLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

/**
 * Class LogApiRequestTest
 * 
 * Tests the LogApiRequest middleware to ensure that all API requests are
 * correctly logged to the database with appropriate metadata (IP, body, headers, etc.).
 */
class LogApiRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the middleware correctly logs a standard API request to the database.
     */
    public function test_it_logs_api_request_to_database()
    {
        $middleware = app(LogApiRequest::class);
        $request = Request::create('/api/test', 'POST', ['foo' => 'bar']);
        $request->setLaravelSession($this->app['session']->driver());
        
        $response = new Response('OK', 200);

        $middleware->handle($request, function() use ($response) {
            return $response;
        });

        $this->assertDatabaseHas('api_logs', [
            'method' => 'POST',
            'url' => 'http://localhost/api/test',
            'response_status' => 200,
        ]);

        $log = ApiLog::first();
        $this->assertEquals(['foo' => 'bar'], $log->body);
    }

    /**
     * Test if the middleware correctly identifies and logs the authenticated user's ID.
     */
    public function test_it_logs_user_id_if_authenticated()
    {
        $user = \App\Models\User::factory()->create();
        Auth::login($user);

        $middleware = app(LogApiRequest::class);
        $request = Request::create('/api/test', 'GET');
        $response = new Response('OK', 200);

        $middleware->handle($request, function() use ($response) {
            return $response;
        });

        $this->assertDatabaseHas('api_logs', [
            'user_id' => $user->id,
            'method' => 'GET',
        ]);
    }
}
