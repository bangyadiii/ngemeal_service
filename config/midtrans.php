<?php

return [
    'mercant_id' => env('MIDTRANS_MERCHAT_ID'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY'),

    'is_production' => env("MIDTRANS_IS_PRODUCTION", false),
    'is_sanitized' => env("MIDTRANS_IS_SANITIZED", true),
    'is_3ds' => env("MIDTRANS_IS_3DS", false),
];
