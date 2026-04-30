<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class AuthTest
 * 
 * Feature tests for the Authentication system, covering user registration,
 * login flow, and 2FA requirements.
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if a new user can successfully register and receive 2FA setup info.
     */
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user',
                'two_factor_secret',
                'qr_code_url'
            ]);
    }

    /**
     * Test if the login flow correctly identifies users who require 2FA verification.
     */
    public function test_user_can_login_but_requires_2fa()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'google2fa_secret' => 'B3O6B6X6X6X6X6X6',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '2FA verification required',
                'requires_2fa' => true
            ]);
    }
}
