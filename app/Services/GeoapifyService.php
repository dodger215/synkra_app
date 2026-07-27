<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoapifyService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.geoapify.api_key');
        $this->baseUrl = config('services.geoapify.base_url');
    }

    /**
     * Calculate route between two points
     */
    public function getRoute(array $from, array $to)
    {
        $url = "{$this->baseUrl}/routing";

        try {
            $response = Http::get($url, [
                'waypoints' => "{$from['latitude']},{$from['longitude']}|{$to['latitude']},{$to['longitude']}",
                'mode' => 'drive',
                'apiKey' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['features'])) {
                    $properties = $data['features'][0]['properties'];
                    return [
                        'distance' => $properties['distance'] / 1000, // km
                        'time' => $properties['time'] / 60, // minutes
                        'geometry' => $data['features'][0]['geometry'],
                    ];
                }
            }
            Log::error("Geoapify Routing Error: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Geoapify Routing Exception: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Search for a location (Geocoding)
     */
    public function search($text)
    {
        $url = "{$this->baseUrl}/geocode/search";

        try {
            $response = Http::get($url, [
                'text' => $text,
                'apiKey' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error("Geoapify Geocoding Exception: " . $e->getMessage());
        }

        return null;
    }
}
