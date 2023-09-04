<?php
namespace App\controllers;

use \App\models\UsuarioModel;
use \App\controllers\MenuController;
use \App\controllers\JuegoController;


class SessionController extends UsuarioModel{

    public $session;
    private $id_usuario;
    private $id_partida;
    private $matriz = array();

    public function cargarPanelNavegacion(){

        $usuarioModel = new UsuarioModel();
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

        $datos_de_inicio_de_sesion = [
                'id_usuario' => $_POST['id_usuario'], 
                 'contrasenia' => $_POST['contrasenia']
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

        session_start();    

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

        $datosUsuario = [
            'id_usuario' => $_POST['id_usuario'],
            'contrasenia' => $_POST['contrasenia'],
            'alias' => $_POST['alias'],
            'email' => $_POST['email'],
            'tipo_usuario' => 'jugador'
        ];

        $resultado = $sesion->registrarNuevo($datosUsuario);

        // echo("<pre>");
        // var_dump($resultado);

        $datos = $this->cargarPanelNavegacion();
        $datos['msj_estado_registro'] = $resultado['descripcion'];

        if($resultado['estado']=='ok') {
            return view('login', $datos );
        }else{
            return view('registrar_usuario', $datos );
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
        setcookie(session_name(), '', time() - 3600);        
        session_destroy();

        $menuController = new MenuController();
        $datos = [];
        $datos['enlaces'] = $menuController->crearMenu('anonimo');
        $datos['sesion_activa'] = false;
        return view('login' , $datos);
    }
}

?>