<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public static function send($to, $message)
    {
        $apiKey = config('delivery.arkasel.api_key');
        $senderId = config('delivery.arkasel.sender_id');

        if (!$apiKey) {
            Log::warning('Arkasel API Key not set. SMS not sent to ' . $to);
            return false;
        }

        try {
            $response = Http::get('https://sms.arkasel.com/sms/api', [
                'action' => 'send-sms',
                'api_key' => $apiKey,
                'to' => $to,
                'from' => $senderId,
                'sms' => $message,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Arkasel SMS Error: ' . $e->getMessage());
            return false;
        }
    }
}
