<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZomatoService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://developers.zomato.com/api/v2.1'; // Standard Zomato API base URL
        $this->apiKey = env('ZOMATO_API_KEY');
    }

    /**
     * Search for restaurants by keyword or location.
     */
    public function search($query = '', $lat = null, $lon = null)
    {
        $params = [
            'q' => $query,
        ];

        if ($lat && $lon) {
            $params['lat'] = $lat;
            $params['lon'] = $lon;
        }

        return $this->request('GET', '/search', $params);
    }

    /**
     * Get restaurant details.
     */
    public function getRestaurant($resId)
    {
        return $this->request('GET', '/restaurant', ['res_id' => $resId]);
    }

    /**
     * Get restaurant reviews.
     */
    public function getReviews($resId)
    {
        return $this->request('GET', '/reviews', ['res_id' => $resId]);
    }

    /**
     * Helper to make API requests.
     */
    protected function request($method, $endpoint, $params = [])
    {
        $response = Http::withHeaders([
            'user-key' => $this->apiKey,
            'Accept' => 'application/json',
        ])->$method($this->baseUrl . $endpoint, $params);

        return $response->json();
    }
}
