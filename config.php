<?php

use Monolog\Logger as MonologLogger;

return [
    'database' => [
        'name' => 'escuela',
        'username' => 'admin',#'root',
        'password' => 'admin',#'Y00s4d14',
        'connection' => 'mysql:host=168.181.185.59',#'mysql:host=127.0.0.1',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ],
    'logger' => [
        'level' => MonologLogger::INFO
    ],
    'twig' => [
        'templates_dir' => __DIR__ . '/../app/views',
        'templates_cache_dir' => __DIR__ . '/../app/views/cache'
    ]
];