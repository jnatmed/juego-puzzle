 <?php

    $router->get('', 'partidaController@mostrarImagenes');
   //  $router->get('', 'partidaController@inicio');
    $router->post('login', 'partidaController@login');
    $router->get('jugar', 'partidaController@cargarPuzzle');
    $router->post('enviar_movimiento', 'partidaController@cargarMovimientosPermitidos');

    $router->get('not_found', 'ProjectController@notFound');
    $router->get('internal_error', 'ProjectController@internalError');
