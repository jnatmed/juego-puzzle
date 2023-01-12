<?php
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../core/bootstrap.php';


use App\Core\App;
use App\Core\Router;
use App\Core\Request;
use App\Core\Logger;
use App\Core\Exceptions\RouteNotFoundException as RouteNotFoundException;

$logger = App::get('logger');


try {
    $render = Router::load( __DIR__ . '/../app/routes.php')
        ->direct(Request::uri(), Request::method());
    // print("nos fue bien..creo   ");
    $logger->info('Status Code: 200');
} catch (RouteNotFoundException $e) {
    http_response_code(404);
    $render = Router::load( __DIR__ . '/../app/routes.php')->direct('not_found', 'GET');
    $logger->debug('Status Code: 404 - Route not found', ["Error" => $e]);
} catch (Exception $e) {
    http_response_code(500);
    $render = Router::load( __DIR__ . '/../app/routes.php')->direct('internal_error', 'GET');
    $logger->error('Status Code: 500 - Internal Server Error', ["Error" => $e]);
}

echo $render;
