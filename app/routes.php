 <?php

   /** login */
   $router->get('login', 'alumnosController@login');
   $router->post('iniciar_session', 'alumnosController@iniciarSession');
   $router->post('registrar_usuario', 'SessionController@registrarUsuarioNuevo');

   
   $router->post('guardar_alumno', 'alumnosController@guardarAlumno');
   $router->post('actualizar_alumno', 'alumnosController@actualizarAlumno');
   $router->post('guardar_padre', 'alumnosController@guardarPadre');
   $router->post('guardar_recibo', 'alumnosController@guardarRecibo');
   $router->post('enviar_nombre', 'alumnosController@buscarAlumno');

  //  Sistema Alumnos
   $router->get('alumnos', 'alumnosController@listar'); # tiene comprobacion de sesion
   $router->get('padres', 'alumnosController@listarPadres'); # tiene comprobacion de sesion
   $router->get('imagenes', 'alumnosController@traerImagenes');# tiene comprobacion de sesion
   $router->get('ver_alumno', 'alumnosController@verAlumno');# tiene comprobacion de sesion
   $router->get('ver_padre', 'alumnosController@verPadre');# tiene comprobacion de sesion
   $router->get('ver_recibos', 'alumnosController@verRecibos');# tiene comprobacion de sesion
   $router->get('nuevo_alumno', 'alumnosController@nuevoAlumno');# tiene comprobacion de sesion
   $router->get('editar_alumno', 'alumnosController@editarAlumno');# tiene comprobacion de sesion
   $router->get('nuevo_padre', 'alumnosController@nuevoPadre');# tiene comprobacion de sesion
   $router->get('nuevo_recibo', 'alumnosController@nuevoRecibo');# tiene comprobacion de sesion
   
   /**juego */
    $router->get('', 'alumnosController@listar');# tiene comprobacion de sesion

   /**proyecto */
    $router->get('not_found', 'ProjectController@notFound');
    $router->get('internal_error', 'ProjectController@internalError');


