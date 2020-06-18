 <?php

    $router->get('', 'partidaController@home');
   //  $router->get('', 'PagesController@home');
    $router->post('envio-movimiento', 'partidaController@mostrar');
   //  $router->get('about', 'PlanillaTurnosController@verPlanillaTurnos');
    $router->get('contact', 'PagesController@contact');

    $router->get('users', 'UsersController@index');
    $router->post('users', 'UsersController@store');

    $router->get('tasks', 'TasksController@index');
    $router->get('tasks/create', 'TasksController@create');
    $router->post('tasks/save', 'TasksController@save');

    $router->get('not_found', 'ProjectController@notFound');
    $router->get('internal_error', 'ProjectController@internalError');
