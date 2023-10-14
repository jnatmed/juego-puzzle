<?php

use App\Core\App;
use App\Core\Database\QueryBuilder;
use App\Core\Database\Connection;
use App\Core\Logger;
use Monolog\Logger as MonologLogger;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Load config
 */
App::bind('config', require __DIR__ . '/../config.php');

/**
 * Load logger object
 */
App::bind('logger', Logger::getLogger(App::get('config')['logger']['level']));

/**
 * Load database connection
 */
App::bind('database', new QueryBuilder(
    Connection::make(App::get('config')['database']),
    App::get('logger')
));

/**
 * Load template engine
 */
$loader = new FilesystemLoader(App::get('config')['twig']['templates_dir']);
$twig = new Environment($loader, array(
    'cache' => App::get('config')['twig']['templates_cache_dir'],
    'debug' => true,
));
App::bind('twig', $twig);
