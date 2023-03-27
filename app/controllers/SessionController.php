<?php
namespace App\controllers;

use \App\models\UsuarioModel;

class SessionController extends UsuarioModel{
    public $session;

    public function login(){
        return view('login', [ 'enlaces' => [['enlace'=>'/','descripcion'=>'Principal'],
                                             ['enlace'=>'/login','descripcion'=>'Login']   
                                                         ]]);
    }

    // public function iniciarSession(){
    //     $this->comprobarSession();
    // }


    public function comprobarSession(){
        session_start();

        echo("<pre>");
        var_dump($_POST);

        if (!isset($_SESSION['id_usuario'])){

            // echo("<pre>");
            var_dump("1) hay sesion iniciada");
            var_dump($_POST);

            if(isset($_POST['id_usuario'])){
                $alumnoModel = new usuarioModel();
                
                $resultado = $alumnoModel->buscarUsuario($_POST['id_usuario']);
        
                // echo("<pre>");
                // var_dump($resultado);
        
                if ($resultado){
        
                    $listado = get_object_vars($resultado[0]);
                    // echo("<pre>");
                    // var_dump($listado);
            // id_usuario
                    $_SESSION['id_usuario'] = $listado['id_usuario'];
                    // return view('listado_alumnos', ['listado' => $this->traerAlumnos(), 'mensaje'=>'SESSION INICIADA']);

                    return True;
                }else{
                    $resultado = 'USUARIO NO REGISTRADO';
                    return view('excepciones',["mensaje"=>$resultado]);
                }
            }else {
                // cuando acceden directamente por url
                // echo("<pre>");
                var_dump("no inicio sesion");
    
                return False;
            }
        }else {
            echo(session_cache_expire());
            return True;
        }
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
    
}

?>