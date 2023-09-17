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

    // private $id_usuario;
    // private $id_partida;
    // private $matriz = array();
    public function cargarPanelNavegacion(){

        // $usuarioModel = new UsuarioModel();
        $menuController = new MenuController();

        $sesion = $this->tieneSesionActiva();
        
        if ($sesion['estado'] == 'ok') {
            
            $tipoUsuario = $_SESSION['tipo_usuario'];
            $alias = $_SESSION['alias'];
            
            // echo("<pre>");
            // var_dump($this->opciones_navbar[$tipoUsuario]);
            
            $datos['enlaces'] = $menuController->crearMenu($tipoUsuario);
            $datos['alias'] = $alias;
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
        session_start();
        
        $datos_de_inicio_de_sesion = [
                'id_usuario' => $_POST['id_usuario'], 
                 'contrasenia' => $_POST['contrasenia']
                ];

        echo "datos $_POST";        
        echo("<pre>");
        var_dump($_POST);

        $sesion_model = new UsuarioModel();

        $estado = $sesion_model->iniciar($datos_de_inicio_de_sesion);

        echo("<pre>");
        var_dump($_SESSION);

        if($estado['estado'] == 'ok'){

            $juegoController = new JuegoController();

            return $juegoController->listado_partidas();

        }

        return view('login' , $this->cargarPanelNavegacion());
    }

    public function tieneSesionActiva(){

        if(session_status() == PHP_SESSION_ACTIVE){

            $usuarioModel = new usuarioModel(); 

            echo("<pre>");
            var_dump($_SESSION);

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

        // echo "<pre>";
        // var_dump($datosUsuario);

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
        $id_usuario = $_GET['id'];

        $sesionModel = new UsuarioModel();
        $resultado = $sesionModel->validarToken($token_sin_validar, $id_usuario);

        if ($resultado['estado'] == 'ok'){
            return view('correo_validado', [
                'mostrarMensaje' => self::CORREO_VALIDADO,
                ...$resultado
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