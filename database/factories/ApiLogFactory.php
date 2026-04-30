<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApiLog>
 */
class ApiLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'ip_address' => $this->faker->ipv4,
            'method' => $this->faker->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
            'url' => $this->faker->url,
            'headers' => [
                'User-Agent' => $this->faker->userAgent,
                'Accept' => 'application/json',
            ],
            'body' => [
                'foo' => 'bar',
            ],
            'response_status' => $this->faker->randomElement([200, 201, 400, 401, 404, 500]),
        ];
    }
}
