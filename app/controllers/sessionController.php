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
            $resultado = $userModel->registrarUsuario($_POST);
            
        }else{
            $msj_error = 'El Usuario ya se encuentra registrado.';
            return view('login', array('mensaje_error_registro' => $msj_error));
        }
    }

    public function login(){
        return view('login');
    }

    public function iniciarSession(){
        session_start();

        $userModel = new UsuarioModel();
        $partidaController = new PartidaController();
        
        /**pregunto si es inicio por facebook **/
        if($_POST['login_por_facebook']=='ok'){
            /** consultar si existe el usuario por facebook **/
            $result_log_face = $userModel->buscarUsuario($_POST['user_log_facebook']);    
            if(!$result_log_face['ya_existe']){
                /**registro usuario de facebook si no existe en la base*/
                $data = ['id_usuario'=>$_POST['user_log_facebook'],
                         'contrasenia'=>$_POST['contrasenia_log_facebook'],
                         'alias'=>$_POST['alias_log_facebook'],
                         'email'=>$_POST['email_log_facebook']
                        ];
                $respuesta = $userModel->registrarUsuario($data);                
                if($respuesta['registro_exitoso']){
                    /**envio a listado de partidas */
                    $_SESSION['id_usuario'] = $_POST['user_log_facebook'];
                    return $partidaController->listarPartidas($_POST['user_log_facebook']);
                }else{
                    $msj_error = 'Error al iniciar session con facebook';
                    return view('login', array('mensaje_error_log' => $msj_error));
                }    
            }else{
                /**guardo en sesion el usuario de facebook */
                $_SESSION['id_usuario'] = $_POST['user_log_facebook'];
                return $partidaController->listarPartidas($_POST['user_log_facebook']);
            }
        }else{
            /**trato de iniciar session comun*/
            $usuario = $_POST['usuario'];
            $contrasenia = $_POST['contrasenia'];    
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
    
}

?>