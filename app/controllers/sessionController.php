<?php
namespace App\controllers;

use \App\models\UsuarioModel;
use \App\models\PartidaModel;

class SessionController{
    public $session;

    public function usuariosRegistrados(){

    }
    public function registrarUsuarioNuevo(){

    }

    public function login(){
        return view('login');
    }

    public function iniciarSession(){
        session_start();

        $usuario = $_POST['usuario'];
        $contrasenia = $_POST['contrasenia'];
        $userModel = new UsuarioModel();
        $partidaController = new PartidaController();
        $resultado = $userModel->iniciarSession($usuario, $contrasenia);
        if($resultado['registrado']){
            if($resultado['contrasenia_correcta']){
                $_SESSION['id_usuario'] = $resultado['id_usuario'];
                $partidaController->listarPartidas($resultado['id_usuario']);
            }else{
                $msj_error = 'CONTRASEÑA INCORRECTA';
                return view('login', array('mensaje_error' => $msj_error));
            }
        }
    }
    
}

?>