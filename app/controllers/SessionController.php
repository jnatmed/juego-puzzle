<?php
namespace App\controllers;

use \App\models\UsuarioModel;

class SessionController extends UsuarioModel{
    public $opciones_navbar = array(
            'jugador' => [['enlace'=>'/ranking','descripcion'=>'Mi Ranking'],
                        ['enlace'=>'/login','descripcion'=>'Login'],
                        ['enlace'=>'/new','descripcion'=>'Nuevo Juego']],
            'admin' => [['enlace'=>'/ranking','descripcion'=>'Ranking'],
                        ['enlace'=>'/login','descripcion'=>'Login'],
                        ['enlace'=>'/new','descripcion'=>'Nuevo Juego'],
                        ['enlace'=>'/listado_usuarios','descripcion'=>'Listado Usuarios'],
                        ['enlace'=>'/partidas','descripcion'=>'Partidas Activas']]
    );        
    public $session;
    private $id_usuario;
    private $id_partida;
    private $matriz = array();

    public function cargarPanelNavegacion(){

        $usuarioModel = new UsuarioModel();

        $sesion = $this->tieneSesionActiva();

        // echo("<pre>");
        // var_dump($sesion);
        
        if ($sesion['estado'] == 'ok') {
            
            $tipoUsuario = $_SESSION['tipo_usuario'];
            
            // echo("<pre>");
            // var_dump($this->opciones_navbar[$tipoUsuario]);
            
            $datos['enlaces'] = $this->opciones_navbar[$tipoUsuario];
            $datos['username'] = $_SESSION['id_usuario'];
            $datos['tipo_usuario'] = $tipoUsuario;
            $datos['sesion_activa'] = true;
            // echo("<pre>");
            // var_dump($datos);            
            
            return $datos;
        }
        
        $datos['enlaces'] = $this->opciones_navbar['jugador'];
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

        session_start();

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
            $tipoUsuario = $_SESSION['tipo_usuario'];
            return view('nuevo_juego' , $this->cargarPanelNavegacion());
        }
        
        return view('login' , $this->cargarPanelNavegacion());
    }

    public function tieneSesionActiva(){

        session_start();

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
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600);

        // echo("cerrarSesion");
        // echo("<pre>");
        // var_dump($_SESSION);

        return $this->login();
    }


}

?>