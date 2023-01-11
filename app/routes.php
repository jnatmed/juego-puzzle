 <?php

   /** login */
   $router->get('login', 'AlumnosController@login');
   $router->post('iniciar_session', 'AlumnosController@iniciarSession');
   $router->post('registrar_usuario', 'SessionController@registrarUsuarioNuevo');

   
   $router->post('guardar_alumno', 'AlumnosController@guardarAlumno');
   $router->post('actualizar_alumno', 'AlumnosController@actualizarAlumno');
   $router->post('guardar_padre', 'AlumnosController@guardarPadre');
   $router->post('guardar_recibo', 'AlumnosController@guardarRecibo');
   $router->post('enviar_nombre', 'AlumnosController@buscarAlumno');

  //  Sistema Alumnos
   $router->get('alumnos', 'AlumnosController@listar'); # tiene comprobacion de sesion
   $router->get('padres', 'AlumnosController@listarPadres'); # tiene comprobacion de sesion
   $router->get('imagenes', 'AlumnosController@traerImagenes');# tiene comprobacion de sesion
   $router->get('ver_alumno', 'AlumnosController@verAlumno');# tiene comprobacion de sesion
   $router->get('ver_padre', 'AlumnosController@verPadre');# tiene comprobacion de sesion
   $router->get('ver_recibos', 'AlumnosController@verRecibos');# tiene comprobacion de sesion
   $router->get('nuevo_alumno', 'AlumnosController@nuevoAlumno');# tiene comprobacion de sesion
   $router->get('editar_alumno', 'AlumnosController@editarAlumno');# tiene comprobacion de sesion
   $router->get('nuevo_padre', 'AlumnosController@nuevoPadre');# tiene comprobacion de sesion
   $router->get('nuevo_recibo', 'AlumnosController@nuevoRecibo');# tiene comprobacion de sesion
   
   /**juego */
    $router->get('', 'AlumnosController@listar');# tiene comprobacion de sesion

   /**proyecto */
    $router->get('not_found', 'ProjectController@notFound');
    $router->get('internal_error', 'ProjectController@internalError');


