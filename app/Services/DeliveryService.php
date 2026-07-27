<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeliveryService
{
    public function getQuote($provider, $data)
    {
        $quote = null;
        if ($provider === 'bolt') {
            $quote = $this->getBoltQuote($data);
        } elseif ($provider === 'yango') {
            $quote = $this->getYangoQuote($data);
        }

        // FALLBACK/MOCK MODE for development if API fails or keys are missing
        if (!$quote) {
            Log::info("Using Mock Quote for {$provider}");
            $distance = 5.5; // Simulated distance in km
            $baseFee = ($provider === 'bolt') ? 15.00 : 12.00;
            $fee = $baseFee + ($distance * 2.5);

            return [
                'quote_id' => 'mock_' . $provider . '_' . uniqid(),
                'delivery_fee' => round($fee, 2),
                'currency' => 'GHS',
                'estimated_delivery' => '25-35 minutes',
                'is_mock' => true
            ];
        }

        return $quote;
    }

    protected function getBoltQuote($data)
    {
        $apiKey = config('delivery.bolt.api_key');
        $baseUrl = config('delivery.bolt.base_url');

        if (!$apiKey || strpos($apiKey, 'your_') === 0) return null;

        // Try different endpoint patterns if one fails
        $endpoints = ['/quote', '/v1/quote', '/delivery/quote'];
        $url = rtrim($baseUrl, '/') . $endpoints[0];

        $payload = [
            'pickup' => $data['pickup'],
            'dropoff' => $data['dropoff'],
            'package' => $data['package'] ?? ['weight' => 1]
        ];

        try {
            $response = Http::withToken($apiKey)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(5)
                ->post($url, $payload);

            if ($response->successful()) {
                return $response->json();
            }
            Log::error("Bolt Quote Error [{$response->status()}]: " . $response->body());
        } catch (\Exception $e) {
            Log::error('Bolt Quote Exception: ' . $e->getMessage());
        }
        return null;
    }

    protected function getYangoQuote($data)
    {
        $apiKey = config('delivery.yango.api_key');
        $baseUrl = config('delivery.yango.base_url');

        if (!$apiKey || strpos($apiKey, 'your_') === 0) return null;

        // Correct Yango endpoint for estimates is often /orders/estimate or /v1/orders/estimate
        $url = rtrim($baseUrl, '/') . '/orders/estimate';

        try {
            $response = Http::withHeaders([
                'YaTaxi-Api-Key' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->timeout(5)
            ->post($url, [
                'pickup' => [
                    'address' => $data['pickup']['address'],
                    'lat' => (float)$data['pickup']['latitude'],
                    'lng' => (float)$data['pickup']['longitude'],
                ],
                'dropoff' => [
                    'address' => $data['dropoff']['address'],
                    'lat' => (float)$data['dropoff']['latitude'],
                    'lng' => (float)$data['dropoff']['longitude'],
                ],
                'package' => $data['package'] ?? ['weight' => 1]
            ]);

            if ($response->successful()) {
                $res = $response->json();
                return [
                    'quote_id' => $res['quote_id'] ?? 'yango_' . uniqid(),
                    'delivery_fee' => $res['price']['amount'] ?? $res['price'] ?? 0,
                    'currency' => $res['price']['currency'] ?? 'GHS',
                    'estimated_delivery' => ($res['estimated_delivery_time'] ?? '25') . ' minutes'
                ];
            }
            Log::error("Yango Quote Error [{$response->status()}]: " . $response->body());
        } catch (\Exception $e) {
            Log::error('Yango Quote Exception: ' . $e->getMessage());
        }
        return null;
    }

    public function createOrder($provider, $data)
    {
        if ($provider === 'bolt') {
            return $this->createBoltOrder($data);
        } elseif ($provider === 'yango') {
            return $this->createYangoOrder($data);
        }
        return null;
    }

    protected function createBoltOrder($data)
    {
        $apiKey = config('delivery.bolt.api_key');
        $baseUrl = config('delivery.bolt.base_url');

        try {
            $response = Http::withToken($apiKey)
                ->post($baseUrl . '/orders', $data);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Bolt Order Exception: ' . $e->getMessage());
        }
        return null;
    }

    protected function createYangoOrder($data)
    {
        $apiKey = config('delivery.yango.api_key');
        $baseUrl = config('delivery.yango.base_url');

        try {
            $response = Http::withToken($apiKey)
                ->post($baseUrl . '/orders', $data);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Yango Order Exception: ' . $e->getMessage());
        }
        return null;
    }
}
