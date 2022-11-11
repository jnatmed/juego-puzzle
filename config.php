<?php

use Monolog\Logger as MonologLogger;

return [
    'database' => [
        'name' => 'id19108229_escuela',
        'username' => 'id19108229_juan',#'root',
        'password' => 'GZ$y7PM=_)!7$p)r',#'Y00s4d14',
        'connection' => 'www.000webhost.com',#'mysql:host=127.0.0.1',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ],
    'logger' => [
        'level' => MonologLogger::INFO
    ],
    'twig' => [
        'templates_dir' => __DIR__ . '/app/views',
        'templates_cache_dir' => __DIR__ . '/app/views/cache'
    ]
];