<?php

declare(strict_types=1);

return [
    'endpoint' => env('S3_HOST', ''),
    'credentials' => [
        'key'    => env('S3_ACCESS_KEY', ''),
        'secret' => env('S3_SECRET_KEY', ''),
    ],
    'region' => env('S3_REGION', 'ru-central-1'),
    'version' => 'latest',
    // You can override settings for specific services
    'Ses' => [
        'region' => 'ru-central-1',
    ],
];
