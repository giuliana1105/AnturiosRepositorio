<?php

return [
    'secret' => env('JWT_SECRET'),
    'ttl' => env('JWT_TTL', 60), // Tiempo de vida del token
    'algo' => env('JWT_ALGO', 'HS256'),
    'keys' => [
        'public' => env('JWT_PUBLIC_KEY'),
        'private' => env('JWT_PRIVATE_KEY'),
    ],
    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),
    'providers' => [
        'user' => App\Models\User::class,
    ],
];
