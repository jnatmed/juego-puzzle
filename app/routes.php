 <?php

   /** login */
   $router->get('login', 'SessionController@login');
   $router->post('iniciar_session', 'SessionController@iniciarSession');
   $router->post('registrar_usuario', 'SessionController@registrarUsuarioNuevo');

   
   $router->post('guardar_alumno', 'alumnosController@guardarAlumno');
   $router->post('actualizar_alumno', 'alumnosController@actualizarAlumno');
   $router->post('guardar_padre', 'alumnosController@guardarPadre');
   $router->post('guardar_recibo', 'alumnosController@guardarRecibo');
   $router->post('enviar_nombre', 'alumnosController@buscarAlumno');

  //  Sistema Alumnos
   $router->get('alumnos', 'alumnosController@listar');
   $router->get('padres', 'alumnosController@listarPadres');
   $router->get('imagenes', 'alumnosController@traerImagenes');
   $router->get('ver_alumno', 'alumnosController@verAlumno');
   $router->get('ver_padre', 'alumnosController@verPadre');
   $router->get('ver_recibos', 'alumnosController@verRecibos');
   $router->get('nuevo_alumno', 'alumnosController@nuevoAlumno');
   $router->get('editar_alumno', 'alumnosController@editarAlumno');
   $router->get('nuevo_padre', 'alumnosController@nuevoPadre');
   $router->get('nuevo_recibo', 'alumnosController@nuevoRecibo');
   
   /**juego */
    $router->get('', 'alumnosController@listar');

   /**proyecto */
    $router->get('not_found', 'ProjectController@notFound');
    $router->get('internal_error', 'ProjectController@internalError');


