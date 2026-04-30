<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserRepositoryTest
 * 
 * Tests the UserRepository to ensure it correctly interacts with the User model
 * and abstracts data access logic for user-related operations.
 */
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository();
    }

    /**
     * Test if the repository can correctly create a new user.
     */
    public function test_it_can_create_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ];

        $user = $this->repository->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    /**
     * Test if the repository can find a user by their email address.
     */
    public function test_it_can_find_user_by_email()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $foundUser = $this->repository->findByEmail('test@example.com');

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    /**
     * Test if the repository returns null when searching for a non-existent email.
     */
    public function test_it_returns_null_if_user_not_found_by_email()
    {
        $foundUser = $this->repository->findByEmail('nonexistent@example.com');

        $this->assertNull($foundUser);
    }
}
