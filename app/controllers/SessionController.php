<?php
namespace App\controllers;

use \App\models\UsuarioModel;
use \App\models\AlumnosModel;
use \App\models\AlumnosController as AlumnControl;

class SessionController extends AlumnosModel{
    public $session;

    public function login(){
        return view('login', [ 'enlaces' => [['enlace'=>'/','descripcion'=>'Principal'],
                                             ['enlace'=>'/login','descripcion'=>'Login']   
                                            ]]);
    }

    public function logout(){
        $_SESSION = array();
        setcookie(session_name(), '', time()-2592000, '/');
        session_destroy();
        return view('login');
    }

    public function iniciarSession(){
        
        $alumnoModel = new AlumnosModel();
        // BUSCO AL USUARIO
        $resultado = $alumnoModel->buscarUsuario($_POST['id_usuario']);
        if ($resultado['resultado']){ // SI LO ENCUENTRO, VERIFICO LA CONTRASEÑA INGRESADA
            $listado = $resultado['datos'];
            if (isset($_POST['id_usuario'])==$listado['id_usuario'] &&
                isset($_POST['contrasenia'])==$listado['contrasenia']){
                // LOGIN CORRECTO => comienza el session_start y GUARDO LA INFO del formulario en EL ARREGLO DE $_SESSION
                session_start();    
                [$_SESSION['id_usuario'],$_SESSION['contrasenia']] = [$_POST['id_usuario'],$_POST['contrasenia']];

                $alumnos = new AlumnosController();
                return view('listado_alumnos', ['listado' => $alumnos->traerAlumnos(), 'mensaje'=>'']);
                /*return true; /* DEVUELVO verdadero indicando que se 
                                inicio correctamente la session y se 
                                cargo el arreglo asociativo $_SESSION*/
            }else{
                // CONTRASEÑA INCORRECTA
                $resultado = 'CONTRASEÑA INCORRECTA';
                return view('excepciones',["mensaje"=>$resultado]);
                }
        }else{
            // ID USUARIO NO ENCONTRADO
            $resultado = 'ID USUARIO NO ENCONTRADO';
            return view('excepciones',["mensaje"=>$resultado]);
            
        }
    }

    public function comprobarSession() {  
            // echo("<pre>");
            session_start();
            // echo "- comprobarSession() => ";
            // echo "\$_SESSION: ";
            // var_dump($_SESSION);
    
            if (isset($_SESSION['id_usuario']) && 
                isset($_SESSION['contrasenia'])){
                // echo ("SESION INICIADA..");
                $alumnoModel = new AlumnosModel();
                $resultado = $alumnoModel->buscarUsuario($_SESSION['id_usuario']);
               
                if ($resultado['resultado']){ // SI LO ENCUENTRO, VERIFICO LA CONTRASEÑA INGRESADA
                    $listado = $resultado['datos'];
                    $_SESSION['id_usuario'] = $listado['id_usuario'];
                    // return view('listado_alumnos', ['listado' => $this->traerAlumnos(), 'mensaje'=>'SESSION INICIADA']);
                    return True;
                }else{
                    // usuario no encontrado..raro    
                    return false;
                }

            }else{               
                // sesion no iniciada
                return false;
            }
    }

    public function usuariosRegistrados(){

    }
    public function registrarUsuarioNuevo(){
        $userModel = new AlumnosModel();
        $resultado = $userModel->buscarUsuario($_POST['id_usuario']);
        if(!$resultado['resultado']){
            return $this->db->insert('usuario',[
                                            'id_usuario'=>$_POST['id_usuario'],
                                            'contrasenia'=>$_POST['contrasenia'],
                                            'alias'=>$_POST['alias'],
                                            'email'=>$_POST['email']
            ]);            
        }else{
            $msj_error = 'El Usuario ya se encuentra registrado.';
            return view('login', ['mensaje_error_registro' => $msj_error]);
        }
    }

}

?>