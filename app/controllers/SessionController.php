<?php
namespace App\controllers;

use \App\models\UsuarioModel;
use \App\models\AlumnosModel;

class SessionController extends AlumnosModel{
    public $session;

    public function usuariosRegistrados(){

    }
    public function registrarUsuarioNuevo(){
        session_start();

        $userModel = new UsuarioModel();
        $resultado = $userModel->buscarUsuario($_POST['id_usuario']);
        if(!$resultado['ya_existe']){
            $resultado = $userModel->registrarUsuario($_POST);
            
        }else{
            $msj_error = 'El Usuario ya se encuentra registrado.';
            return view('login', array('mensaje_error_registro' => $msj_error));
        }
    }

    public function login(){
        return view('login');
    }
    
}

?>