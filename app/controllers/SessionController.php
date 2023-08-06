<?php
namespace App\controllers;

use \App\models\UsuarioModel;

class SessionController extends UsuarioModel{
    public $opciones_navbar = array(
            'jugador' => [['enlace'=>'/','descripcion'=>'Ranking'],
                        ['enlace'=>'/login','descripcion'=>'Login'],
                        ['enlace'=>'/new','descripcion'=>'Nuevo Juego']],
            'admin' => [['enlace'=>'/','descripcion'=>'Ranking'],
                        ['enlace'=>'/login','descripcion'=>'Login'],
                        ['enlace'=>'/new','descripcion'=>'Nuevo Juego'],
                        ['enlace'=>'/usuarios','descripcion'=>'Listado Usuarios'],
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
             return ['enlaces' => $this->opciones_navbar[$tipoUsuario] ];
        }

        return ['enlaces' => $this->opciones_navbar['jugador'] ];

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
            return view('login' , ['enlaces' => $this->opciones_navbar[$tipoUsuario] ]);
        }
        return view('login' , ['enlaces' => $this->opciones_navbar['jugador'] ]);
    }

    public function tieneSesionActiva(){

        session_start();

        // var_dump($id_usuario);
        // echo("<br>tieneSesionActiva");
        // echo("<pre>");
        var_dump($_SESSION);
        var_dump(isset($_SESSION['id_usuario']));

        if(isset($_SESSION['id_usuario'])){

            $usuarioModel = new usuarioModel(); 

            if($usuarioModel->existeUsuario($_SESSION['id_usuario'])){

                    // $this->setIdUsuario($_SESSION['id_usuario']);
                    // $this->setMatriz[$_SESSION['matriz']];
                    // $this->setIdPartida($_SESSION['id_partida']);

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

    public function cerrarSesion() {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600);

        // echo("cerrarSesion");
        // echo("<pre>");
        // var_dump($_SESSION);

        return $this->login();
    }

    public function registrar_usuario(){
        
    }

}

?>