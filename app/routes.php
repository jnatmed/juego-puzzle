 <?php

    $router->get('', 'partidaController@mostrarImagenes');
   //  $router->get('', 'partidaController@inicio');
    $router->post('iniciar_session', 'SessionController@iniciarSession');
    $router->get('login', 'SessionController@login');

    $router->get('jugar', 'partidaController@cargarPuzzle');
    $router->post('enviar_movimiento', 'partidaController@cargarMovimientosPermitidos');

    $router->get('not_found', 'ProjectController@notFound');
    $router->get('internal_error', 'ProjectController@internalError');
