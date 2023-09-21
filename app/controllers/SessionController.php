<?php
namespace App\controllers;

use \App\models\UsuarioModel;
use \App\controllers\MenuController;
use \App\controllers\JuegoController;
use \App\controllers\ValidadorController;
use \App\controllers\CorreoController;

class SessionController extends UsuarioModel{

    public $session;
    const CORREO_VALIDADO = 'CORREO VALIDADO CON EXITO';

    public function cargarPanelNavegacion(){


        $menuController = new MenuController();

        $sesion = $this->tieneSesionActiva();
        
        if ($sesion['estado'] == 'ok') {
            
            $tipoUsuario = $_SESSION['tipo_usuario'];
            $alias = $_SESSION['alias'];
                      
            $datos['enlaces'] = $menuController->crearMenu($tipoUsuario);
            $datos['alias'] = $alias;
            $datos['tipo_usuario'] = $tipoUsuario;
            $datos['sesion_activa'] = true;
            
            return $datos;
        }
        
        $datos['enlaces'] = $menuController->crearMenu('anonimo');
        $datos['sesion_activa'] = false;
         
        return $datos;

    }

    public function login(){

        // Iniciar la sesión (si aún no está iniciada)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return view('login' , $this->cargarPanelNavegacion());
    }

    public function registro() {
        return view('registrar_usuario' , $this->cargarPanelNavegacion());
    }

    public function iniciarSession(){

        // Iniciar la sesión (si aún no está iniciada)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $datos_de_inicio_de_sesion = [
                'id_usuario' => $_POST['id_usuario'], 
                 'contrasenia' => $_POST['contrasenia']
                ];

        $sesion_model = new UsuarioModel();

        $estado = $sesion_model->iniciar($datos_de_inicio_de_sesion);

        if($estado['estado'] == 'ok'){

            $juegoController = new JuegoController();

            return $juegoController->listado_partidas();

        } 
        return view('login' , [
            'msj_estado_registro' => 'usuario o contraseña incorrectos',
            ...$this->cargarPanelNavegacion()]);
    }

    public function tieneSesionActiva(){

         // Iniciar la sesión (si aún no está iniciada)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuarioModel = new usuarioModel(); 

        if(isset($_SESSION['id_usuario'])){

            if($usuarioModel->existeUsuario($_SESSION['id_usuario'])){

                    return ['estado' => 'ok',
                            'codigo' => 200,
                            'descripcion' => 'sesion iniciada con exito!'];

            }else{
                return ['estado' => 'error',
                        'codigo' => 2,
                        'descripcion' => 'usuario inexistente en la base'];
            }
        }else{
            return ['estado' => 'error',
            'codigo' => 3,
            'descripcion' => 'hay sesion activa pero es anonima'];
        }

    }

    public function registrar_usuario(){

        $sesion = new UsuarioModel(); 
        $datos_mailer = require __DIR__ . '/../../config.php';


        /**verifico que no haya pida el recurso en un post vacio, yendo directamente a la url */
        if(!isset($_POST['id_usuario'])){
            /** lo redirigo directmente a la pagina del registro con el mensaje de error */
            $datos['msj_estado_registro'] = 'Formulario de registro vacio';            
            return view('registrar_usuario', [
                ...$datos,
                ...$this->cargarPanelNavegacion()
                ] );
        }

        /**paso a controlar la contraseña */
        $contrasenia = $_POST['contrasenia'];

        $validadorController = new ValidadorController(
            new CorreoController(
                $datos_mailer['mailer']
            ),
            $datos_mailer['mailer']
        );

        $destinatario = $_POST['email'];
        $idUsuario = $_POST['id_usuario'];
        $alias_to = $_POST['alias'];

        $resultado_validacion_contrasenia = $validadorController->validarContrasena($contrasenia);
       
        /** si la contraseña esta validada entonces la hasheo*/
        if($resultado_validacion_contrasenia['estado'] == 'validado'){
            $hashContrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);
        }else{
            /** caso contrario envio error a la pagina de registro */
            $datos['msj_estado_registro'] = $resultado_validacion_contrasenia['descripcion'];            
            return view('registrar_usuario', [
                ...$datos,
                ...$this->cargarPanelNavegacion()
                ] );
        }

        $token_validacion = $validadorController->generarToken($destinatario, $idUsuario);

        $enviado = $validadorController->enviarCorreoValidacion($destinatario, 
                                                                $token_validacion, 
                                                                $alias_to,
                                                                $idUsuario);

        $datosUsuario = [
            'id_usuario' => $_POST['id_usuario'],
            'contrasenia' => $hashContrasenia,
            'alias' => $_POST['alias'],
            'email' => $_POST['email'],
            'tipo_usuario' => 'jugador',
            'estado_validacion' => 'pendiente',
            'token_validacion' => $token_validacion,
            'fecha_expiracion_enlace' => $validadorController->generarFechaExpiracion(),
        ];


        $resultado = $sesion->registrarNuevo($datosUsuario);

        $datos = $this->cargarPanelNavegacion();
        $datos['msj_estado_registro'] = $resultado['descripcion'];

        if ($resultado['estado'] === 'ok') {
            $vista = 'listado_partidas';
        } else {
            $vista = 'registrar_usuario';
        }
        
        return view($vista, $datos);

    }

    public function confirmarCorreo(){
        
        $token_sin_validar = $_GET['token'];
        $id_usuario = $_GET['id'];

        $sesionModel = new UsuarioModel();
        $resultado = $sesionModel->validarToken($token_sin_validar, $id_usuario);

        if ($resultado['estado'] == 'ok'){
            return view('correo_validado', [
                'mostrarMensaje' => self::CORREO_VALIDADO,
                ...$resultado,
                ...$this->cargarPanelNavegacion()
            ]);
        }
    }

    public function listadoUsuarios(){
        $sesion = new UsuarioModel();

        $listadoUsuarios = $sesion->listadoUsuarios();

        $listado = [
            'listado_usuarios' => $listadoUsuarios,
            ...$this->cargarPanelNavegacion()
        ];

        return view('listado_usuarios', $listado);
    }

    public function datosUsuario(){
        $sesion = new UsuarioModel();
       
        $resultado = $this->tieneSesionActiva();
        
        if($resultado['estado'] === 'ok'){

            $vista = 'datos_usuario';
            $datos = $sesion->traerDatosUsuario($_SESSION['id_usuario']);

            $datos['msj_estado_registro'] = $datos['descripcion'];

            if($datos['estado'] === 'ok'){
                $datos = [
                    ...$datos,
                    'datos_usuario' => [
                        'ID de Usuario' => $datos['id_usuario'],
                        'Correo' => $datos['email'] . " (" . $datos['estado_validacion'] .")",
                        'Alias' => $datos['alias'],
                    ],
                    ...$this->cargarPanelNavegacion()
                ];
            }

        }else{
            $vista = 'login';
            $datos = $this->cargarPanelNavegacion();
        }

        return view($vista, $datos);
    }

    public function cerrarSesion() {

        // Iniciar la sesión (si aún no está iniciada)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Destruir la sesión (si está iniciada)
        if (session_id() !== '') {
            // Borra todas las variables de sesión
            $_SESSION = [];

            // Borra la cookie de sesión (opcional)
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 3600,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Finalmente, destruir la sesión
            session_destroy();
        }        

        $menuController = new MenuController();
        $datos = [];
        $datos['enlaces'] = $menuController->crearMenu('anonimo');

        return view('login', $datos);
    }
}

?>