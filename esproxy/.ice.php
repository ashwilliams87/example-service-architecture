<?php
return [
    'vendor' => 'lan',
    'name' => 'esproxy',
    'namespace' => 'Lan\\Ebs\\Esproxy\\',
    'alias' => 'Esproxy',
    'description' => 'Lan EBS Api based on Laravel',
    'url' => 'https://esproxy.lanbook.com',
    'type' => 'project',
    'context' => '',
    'pathes' => [
        'config' => 'config/',
        'source' => 'src/',
        'resource' => 'resource/',
    ],
    'environments' => [
        'prod' => [
            'pattern' => [
                '/^esproxy\.lanbook\.com$/',
                '/^esproxy\.stage\.lanbook\.com$/',
            ]
        ],
        'test' => [
            'pattern' => [
                '/^esproxy\.ebs\.local$/',
                '/^espoxy$/',
                '/^develop1$/'
            ]
        ],
        'dev' => [
            'pattern' => [
                '/^esproxy\.ebs\.local$/',
                '/^espoxy$/',
                '/^develop1$/'
            ]
        ],
    ],
    'modules' => [
        'lan/ice-fork' => [],
    ],
];
