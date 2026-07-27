<?php

namespace App\Services\Marketing;

interface MarketingPlatformInterface
{
    public function getAuthUrl(): string;
    public function getAccessToken(string $code): array;
    public function createCampaign(array $data): array;
    public function getCampaignStats(string $externalId): array;
}
