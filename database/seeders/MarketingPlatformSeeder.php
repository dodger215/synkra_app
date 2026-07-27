<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketingPlatform;

class MarketingPlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            ['platform_name' => 'Meta', 'is_available' => true],
            ['platform_name' => 'Google', 'is_available' => true],
            ['platform_name' => 'TikTok', 'is_available' => true],
            ['platform_name' => 'X', 'is_available' => true],
        ];

        foreach ($platforms as $platform) {
            MarketingPlatform::updateOrCreate(
                ['platform_name' => $platform['platform_name']],
                ['is_available' => $platform['is_available']]
            );
        }
    }
}
