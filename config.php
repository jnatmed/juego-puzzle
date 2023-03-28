<?php

use Monolog\Logger as MonologLogger;

return [
    'database' => [
        'name' => 'juego_puzzle',
        'username' => 'root',#'admin'
        'password' => 'y00s4d14', #'admin'
        'connection' => 'mysql:host=127.0.0.1', # 'mysql:host=168.181.185.59'
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