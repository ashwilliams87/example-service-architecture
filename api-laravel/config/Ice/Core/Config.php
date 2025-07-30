<?php

use Ebs\Action\GarbageCollector_RemoveOldFiles;
use Ebs\Security\Ebs;
use Ice\Core\Environment;
use Ice\Core\Request;
use Ice\Core\Security;

return [
    Request::class => [
        'multiLocale' => 0,
        'locale' => 'ru',
        'cors' => [],
    ],
    Environment::class => [
        'environments' => [
            '/^api-laravel\.ebs\.local$/' => 'development',
            '/^api-laravel-internal$/' => 'development',
            '/^api\.landev\.ru$/' => 'test',
            '/^api-laravel$/' => 'test',
        ],
    ],

    Security::class => [
        'defaultClassName' => Ebs::class,
        'jwt_refresh_auto' => 1,
    ],

    GarbageCollector_RemoveOldFiles::class => [
        'directories' => [
            'var/data' => 4,
        ]
    ],
];
