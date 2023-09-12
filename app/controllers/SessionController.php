<?php
namespace App\controllers;

use \App\models\UsuarioModel;
use \App\controllers\MenuController;
use \App\controllers\JuegoController;
use \App\controllers\ValidadorController;
use \App\controllers\CorreoController;

class SessionController extends UsuarioModel{

    public $session;
    // private $id_usuario;
    // private $id_partida;
    // private $matriz = array();

    public function cargarPanelNavegacion(){

        // $usuarioModel = new UsuarioModel();
        $menuController = new MenuController();

        $sesion = $this->tieneSesionActiva();
        
        if ($sesion['estado'] == 'ok') {
            
            $tipoUsuario = $_SESSION['tipo_usuario'];
            
            // echo("<pre>");
            // var_dump($this->opciones_navbar[$tipoUsuario]);
            
            $datos['enlaces'] = $menuController->crearMenu($tipoUsuario);
            $datos['id_usuario'] = $_SESSION['id_usuario'];
            $datos['tipo_usuario'] = $tipoUsuario;
            $datos['sesion_activa'] = true;
            // echo("<pre>");
            // var_dump($datos);            
            
            return $datos;
        }
        
        $datos['enlaces'] = $menuController->crearMenu('anonimo');
        $datos['sesion_activa'] = false;
         
        return $datos;

    }

    public function login(){

        return view('login' , $this->cargarPanelNavegacion());
    }

    public function registro() {
        return view('registrar_usuario' , $this->cargarPanelNavegacion());
    }

    public function iniciarSession(){

        /**
         *  si la sesion no esta activa */ 
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $contrasenia = $_POST['contrasenia'];
        $hashContrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);

        $datos_de_inicio_de_sesion = [
                'id_usuario' => $_POST['id_usuario'], 
                 'contrasenia' => $hashContrasenia
                ];

        // echo("<pre>");
        // var_dump($id_usuario);
        // var_dump($pass);

        $sesion_model = new UsuarioModel();

        $estado = $sesion_model->iniciar($datos_de_inicio_de_sesion);

        // echo("<pre>");
        // var_dump($estado);

        if($estado['estado'] == 'ok'){

            $juegoController = new JuegoController();

            return $juegoController->listado_partidas();

        }

        return view('login' , $this->cargarPanelNavegacion());
    }

    public function tieneSesionActiva(){

        // Deshabilito las notificaciones de error relacionadas con las sesiones
        // error_reporting(E_ALL & ~E_NOTICE);

        // Inicia la sesión solo si no está activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
                // Verificar si la sesión ha expirado
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1440)) {
            session_unset();   // Desvincular todas las variables de sesión
            session_destroy(); // Destruir la sesión
            echo "La sesión ha expirado.";
            // Actualizar la marca de tiempo de la última actividad
            $_SESSION['LAST_ACTIVITY'] = time();
        }

        if(isset($_SESSION['id_usuario'])){

            $usuarioModel = new usuarioModel(); 

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
                    'codigo' => 1,
                    'descripcion' => 'sesion no iniciada'];
        }
    }

    public function registrar_usuario(){

        $sesion = new UsuarioModel(); 

        $contrasenia = $_POST['contrasenia'];
        $hashContrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);

        $datos_mailer = require __DIR__ . '/../../config.php';

        $validadorController = new ValidadorController(
            new CorreoController(
                $datos_mailer['mailer']
            ),
            $datos_mailer['mailer']
        );

        $destinatario = $_POST['email'];
        $idUsuario = $_POST['id_usuario'];
        $alias_to = $_POST['alias'];

        $token_validacion = $validadorController->generarToken($destinatario, $idUsuario);

        $enviado = $validadorController->enviarCorreoValidacion($destinatario, $token_validacion, $alias_to);


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


        if($resultado['estado']=='ok') {
            return view('login', $datos );
        }else{
            return view('registrar_usuario', $datos );
        }
    }

    public function confirmarCorreo(){
        $token_sin_validar = $_GET['token'];
        $sesion = $this->tieneSesionActiva();
        
        var_dump($token_sin_validar);
        
        if ($sesion['estado'] == 'ok') {
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

    public function cerrarSesion() {
        // Verificar si la sesión está iniciada
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Destruir la sesión
            setcookie(session_name(), '', time() - 3600);
            session_destroy();
        }
        
        $menuController = new MenuController();
        $datos = [];
        $datos['enlaces'] = $menuController->crearMenu('anonimo');
        
        // La sesión se ha cerrado correctamente
        $datos['sesion_activa'] = false;
    
        return view('login', $datos);
    }
}

?>