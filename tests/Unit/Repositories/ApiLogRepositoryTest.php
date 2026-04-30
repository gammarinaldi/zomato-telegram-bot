<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\ApiLog;
use App\Repositories\Eloquent\ApiLogRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ApiLogRepositoryTest
 * 
 * Tests the ApiLogRepository to ensure it correctly logs API requests and
 * provides efficient retrieval mechanisms for monitoring purposes.
 */
class ApiLogRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ApiLogRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ApiLogRepository();
    }

    /**
     * Test if the repository can correctly create an API log entry.
     */
    public function test_it_can_create_api_log()
    {
        $data = [
            'method' => 'GET',
            'url' => 'http://localhost/api/test',
            'ip_address' => '127.0.0.1',
            'response_status' => 200,
        ];

        $log = $this->repository->create($data);

        $this->assertInstanceOf(ApiLog::class, $log);
        $this->assertDatabaseHas('api_logs', ['url' => 'http://localhost/api/test']);
    }

    /**
     * Test if the repository can retrieve all logs in a paginated format.
     */
    public function test_it_can_get_all_paginated_logs()
    {
        ApiLog::factory()->count(10)->create();

        $logs = $this->repository->getAllPaginated(5);

        $this->assertCount(5, $logs);
        $this->assertEquals(10, $logs->total());
    }
}
