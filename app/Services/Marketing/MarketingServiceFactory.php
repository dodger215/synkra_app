<?php

namespace App\Services\Marketing;

class MarketingServiceFactory
{
    public static function make(string $platformName): MarketingPlatformInterface
    {
        return match (strtolower($platformName)) {
            'meta' => new MetaMarketingService(),
            'google' => new GoogleAdsService(),
            default => throw new \InvalidArgumentException("Platform [{$platformName}] not supported."),
        };
    }
}
