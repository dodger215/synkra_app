<?php

namespace App\Services\Marketing;

use Illuminate\Support\Facades\Http;

class MetaMarketingService implements MarketingPlatformInterface
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.facebook.client_id');
        $this->clientSecret = config('services.facebook.client_secret');
        $this->redirectUri = config('services.facebook.redirect');
    }

    public function getAuthUrl(): string
    {
        return "https://www.facebook.com/v19.0/dialog/oauth?client_id={$this->clientId}&redirect_uri={$this->redirectUri}&scope=ads_management,ads_read";
    }

    public function getAccessToken(string $code): array
    {
        $response = Http::get("https://graph.facebook.com/v19.0/oauth/access_token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
        ]);

        return $response->json();
    }

    public function createCampaign(array $data): array
    {
        // Integration with Meta Ads API
        return ['external_id' => 'META_' . uniqid(), 'status' => 'active'];
    }

    public function getCampaignStats(string $externalId): array
    {
        return ['reach' => 1000, 'impressions' => 5000, 'spend' => 50.00];
    }
}
