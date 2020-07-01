<?php
namespace App\controllers;

use \App\models\UsuarioModel;
use \App\models\PartidaModel;

class SessionController{
    public $session;

    public function usuariosRegistrados(){

    }
    public function registrarUsuarioNuevo(){
        session_start();

        $userModel = new UsuarioModel();
        $resultado = $userModel->buscarUsuario($_POST['id_usuario']);
        if(!$resultado['ya_existe']){
            $userModel->registrarUsuario($_POST);
        }
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
        if($resultado['logueo']){
            $_SESSION['id_usuario'] = $resultado['id_usuario'];
            return $partidaController->listarPartidas($resultado['id_usuario']);
        }else{
            $msj_error = 'USUARIO O CONTRASEÑA INCORRECTA';
            return view('login', array('mensaje_error_log' => $msj_error));
        }
    }
    
}

?>