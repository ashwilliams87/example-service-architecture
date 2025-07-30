<?php

return [
    'vendor' => 'lan',
    'name' => 'ebs-api-laravel',
    'namespace' => 'Lan\\Ebs\\ApiLaravel\\',
    'alias' => 'ApiLaravel',
    'description' => 'Lan EBS Api based on Laravel',
    'url' => 'https://fs1.e.lanbook.com',
    'type' => 'project',
    'context' => '',
    'pathes' => [
        'config' => 'config/',
        'source' => 'source/',
        'resource' => 'resource/',
    ],
    'environments' => [
        'dev' => [
            'pattern' => [
                '/^api-laravel\.ebs\.local$/',
                '/^api-laravel-internal$/',
                '/^develop1$/'
            ]
        ],
        'test' => [
            'pattern' => [
                '/^api\.landev\.ru$/',
                '/^api-laravel$/',
            ]
        ],
    ],
    'modules' => [
        'lan/ice-fork' => [],
    ],
];
