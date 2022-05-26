 <?php

   /** login */
   $router->get('login', 'SessionController@login');
   $router->post('iniciar_session', 'SessionController@iniciarSession');
   $router->post('registrar_usuario', 'SessionController@registrarUsuarioNuevo');

   /**juego */
    $router->get('alumnos', 'alumnosController@listar');
    $router->get('ver_alumno', 'alumnosController@verAlumno');
    $router->get('', 'partidaController@mostrarImagenes');
    $router->get('jugar', 'partidaController@cargarPuzzle');
    $router->get('listar_partidas', 'partidaController@listar_partidas');
    $router->get('nuevo_rompecabezas', 'partidaController@nuevo_rompecabezas');
    $router->get('guardar_rompecabezas', 'partidaController@guardar_rompecabezas');
    $router->post('enviar_movimiento', 'partidaController@cargarMovimientosPermitidos');

   /**proyecto */
    $router->get('not_found', 'ProjectController@notFound');
    $router->get('internal_error', 'ProjectController@internalError');


