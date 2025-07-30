<?php

namespace Ice\Core;

use Ebs\Security\Ebs;
use Ice\Router\Symfony;

return [
    Environment::class => [
        'environments' => [
            '/^esproxy/' => 'production',
            '/\.com$/' => 'production',
            '/\.lanbook\.ru$/' => 'production',
            '/\.landev\.ru$/' => 'test',
            '/\.local$/' => 'development',
            '/^home\./' => 'development',
            '/^dp\./' => 'development',
        ]
    ],
    Security::class => [
        'defaultClassName' => Ebs::class,
        'jwt_refresh_auto' => 1,
    ],
    Router::class => [
        'defaultClassName' => Symfony::class,
    ],
];