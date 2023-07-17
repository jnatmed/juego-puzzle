<?php
namespace App\controllers;

use \App\models\UsuarioModel;

class SessionController extends UsuarioModel{
    public $session;
    private $id_usuario;
    private $id_partida;
    private $matriz = array();

    public function setIdUsuario($id){$this->id_usuario = $id;}
    public function setMatriz($matriz){$this->matriz = $matriz;}
    public function setIdPartida($id_partida){$this->id_partida = $id_partida;}

    public function getIdUsuario(){return $this->idUsuario;}
    public function getMatriz(){return $this->matriz;}
    public function getIdPartida(){return $this->id_partida;}

    public function login(){
        
        return view('login', [ 'enlaces' => [['enlace'=>'/','descripcion'=>'Ranking'],
                                             ['enlace'=>'/login','descripcion'=>'Login'],
                                             ['enlace'=>'/new','descripcion'=>'Nuevo Juego']   
                                                         ]]);
    }

    public function tieneSesionActiva(){
        session_start();

        if(isset($_SESSION['id_usuario'])){

            $usuarioModel = new usuarioModel();

            if($usuarioModel->existeUsuario($_SESSION['id_usuario'])){
                if($usuarioModel->contraseniaCorrecta($_SESSION['contrasenia'])){

                    $this->setIdUsuario($_SESSION['id_usuario']);
                    $this->setMatriz[$_SESSION['matriz']];
                    $this->setIdPartida($_SESSION['id_partida']);

                    return ['estado' => 'ok',
                            'codigo' => 200,
                            'descripcion' => 'sesion iniciada con exito!'];
                }else{
                    return ['estado' => 'error',
                            'codigo' => 3,
                            'descripcion' => 'contraseña incorrecta'];
                }
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
    
}

?>