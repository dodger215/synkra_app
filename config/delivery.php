<?php

return [
    'bolt' => [
        'api_key' => env('BOLT_DELIVERY_API_KEY'),
        'base_url' => env('BOLT_DELIVERY_BASE_URL', 'https://api.bolt.eu/v1/delivery'),
    ],
    'yango' => [
        'api_key' => env('YANGO_DELIVERY_API_KEY'),
        'base_url' => env('YANGO_DELIVERY_BASE_URL', 'https://api.yango.com/api/v1'),
    ],
    'arkasel' => [
        'api_key' => env('ARKESEL_API_KEY'),
        'sender_id' => env('ARKESEL_SENDER_ID', 'Flowexa'),
    ],
];
