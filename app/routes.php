<?php
/**
 * el login llama al view `login.html`.
 * del `login.html` pasa a los post: 
 *    -> `iniciar_session` 
 *    -> `registrar_usuario`   
 */
 $router->get('', 'SessionController@login');# tiene comprobacion de sesion

/**proyecto */
$router->get('not_found', 'ProjectController@notFound');
$router->get('internal_error', 'ProjectController@internalError');

/** sesionController */
   $router->get('login', 'SessionController@login');
   $router->get('nuevo_usuario', 'SessionController@registro');
   $router->get('listado_usuarios', 'SessionController@listadoUsuarios');
   $router->post('iniciar_session', 'SessionController@iniciarSession');
   $router->post('registrar_usuario', 'SessionController@registrar_usuario');
/** logout */
$router->get('cerrar_sesion', 'SessionController@cerrarSesion');

/* JuegoController */  
  $router->get('new','juegoController@new');
  $router->get('ranking', 'juegoController@ranking');
  
  $router->post('enviarEstado','juegoController@reciboEstado');
   // $router->post('guardar_alumno', 'alumnosController@guardarAlumno');
   // $router->post('actualizar_alumno', 'alumnosController@actualizarAlumno');
   // $router->post('guardar_padre', 'alumnosController@guardarPadre');
   // $router->post('guardar_recibo', 'alumnosController@guardarRecibo');
   // $router->post('enviar_nombre', 'alumnosController@buscarAlumno');

  //  Sistema Alumnos
   // $router->get('alumnos', 'alumnosController@listar'); # tiene comprobacion de sesion
   // $router->get('padres', 'alumnosController@listarPadres'); # tiene comprobacion de sesion
   // $router->get('ver_imagenes', 'alumnosController@verImagenes');# tiene comprobacion de sesion
   // $router->get('ver_alumno', 'alumnosController@verAlumno');# tiene comprobacion de sesion
   // $router->get('ver_padre', 'alumnosController@verPadre');# tiene comprobacion de sesion
   // $router->get('ver_recibos', 'alumnosController@verRecibos');# tiene comprobacion de sesion
   // $router->get('nuevo_alumno', 'alumnosController@nuevoAlumno');# tiene comprobacion de sesion
   // $router->get('editar_alumno', 'alumnosController@editarAlumno');# tiene comprobacion de sesion
   // $router->get('nuevo_padre', 'alumnosController@nuevoPadre');# tiene comprobacion de sesion
   // $router->get('nuevo_recibo', 'alumnosController@nuevoRecibo');# tiene comprobacion de sesion
   


