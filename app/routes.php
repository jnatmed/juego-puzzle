<?php
 

/**proyecto */
$router->get('not_found', 'ProjectController@notFound');
$router->get('internal_error', 'ProjectController@internalError');

/** sesionController */
   $router->get('', 'SessionController@login'); 
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
  $router->get('listado_partidas', 'juegoController@listado_partidas');
  $router->get('continuar_partida', 'juegoController@continuar_partida');

  
  $router->post('guardar_estado','juegoController@reciboEstado');
  $router->post('guardar_imagen','juegoController@guardar_imagen');
   


