<?php

return [
    'title' => 'STORAGE SERVICE',
    'version' => '0.0.1',
    'servers' => [
        'local' => [
            'host' => 'http://localhost:8001',
            'description' => 'Local API server',
        ],
        'prod' => [
            'host' => 'https://example.domain',
            'description' => 'Production API server',
        ],
    ],
    'auth_middleware' => 'auth',
    'export' => [
        'path' => './public/swagger.yaml',
        'format' => 'yaml',
    ],
];
