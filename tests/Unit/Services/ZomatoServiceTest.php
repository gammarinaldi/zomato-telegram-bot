<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ZomatoService;
use Illuminate\Support\Facades\Http;

/**
 * Class ZomatoServiceTest
 * 
 * Verifies the functionality of the ZomatoService, ensuring it can correctly
 * communicate with the Zomato API to search for restaurants and fetch details/reviews.
 */
class ZomatoServiceTest extends TestCase
{
    protected ZomatoService $zomatoService;

    /**
     * Set up the test environment by instantiating the ZomatoService.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->zomatoService = new ZomatoService();
    }

    /**
     * Test if the service can correctly search for restaurants using the Zomato API.
     * Fakes the API response and asserts that results are correctly parsed.
     */
    public function test_it_can_search_restaurants()
    {
        Http::fake([
            'developers.zomato.com/api/v2.1/search*' => Http::response([
                'restaurants' => [
                    ['restaurant' => ['name' => 'Pizza Place']],
                    ['restaurant' => ['name' => 'Burger Joint']],
                ]
            ], 200)
        ]);

        $results = $this->zomatoService->search('pizza');

        $this->assertCount(2, $results['restaurants']);
        $this->assertEquals('Pizza Place', $results['restaurants'][0]['restaurant']['name']);
        
        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/search') &&
                   $request['q'] === 'pizza';
        });
    }

    /**
     * Test if the service can correctly fetch restaurant details from the Zomato API.
     */
    public function test_it_can_get_restaurant_details()
    {
        Http::fake([
            'developers.zomato.com/api/v2.1/restaurant*' => Http::response([
                'name' => 'The Great Indian Thali',
                'id' => '12345'
            ], 200)
        ]);

        $details = $this->zomatoService->getRestaurant('12345');

        $this->assertEquals('The Great Indian Thali', $details['name']);
        $this->assertEquals('12345', $details['id']);
    }

    /**
     * Test if the service can correctly fetch user reviews for a restaurant.
     */
    public function test_it_can_get_restaurant_reviews()
    {
        Http::fake([
            'developers.zomato.com/api/v2.1/reviews*' => Http::response([
                'user_reviews' => [
                    ['review' => ['rating' => 5, 'review_text' => 'Great!']],
                ]
            ], 200)
        ]);

        $reviews = $this->zomatoService->getReviews('12345');

        $this->assertCount(1, $reviews['user_reviews']);
        $this->assertEquals(5, $reviews['user_reviews'][0]['review']['rating']);
    }
}
