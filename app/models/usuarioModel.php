<?php
namespace App\models;

use App\Core\Model;
use PDO;
use PDOException;

class UsuarioModel extends Model{
    public $db;
    // private $id_usuario;
    // private $tipo_usuario;

    public function existeUsuario($id_usuario){

        $resultadoConsulta = $this->db->selectOne('usuario','id_usuario',$id_usuario);        

        // echo("<pre>");
        // var_dump($resultadoConsulta);

        if(!empty($resultadoConsulta)) {
            $_SESSION = [
                'id_usuario' => $id_usuario,
                'tipo_usuario' => $resultadoConsulta['tipo_usuario'],
                'contrasenia' => $resultadoConsulta['contrasenia']
            ];
            return true;
        }else{
            return false;
        }  
    }   

    public function iniciar($datosUsuario){

        try {

            // echo("<pre>");
            // var_dump($datosUsuario);

        // echo("<pre>");
        // var_dump($this->db);


            $resultadoConsulta = $this->db->buscarUsuario($datosUsuario);

            // echo("<pre>");
            // var_dump($resultadoConsulta);
            
            if(!empty($resultadoConsulta)){

                    // echo("<pre>");
                    // var_dump($resultadoConsulta);

                    $_SESSION = [
                        'id_usuario' => $resultadoConsulta['id_usuario'],
                        'tipo_usuario' => $resultadoConsulta['tipo_usuario']
                    ];

                    return [
                        'estado' => 'ok',
                        'contrasenia_correcta' => true,
                        'id_usuario' => $resultadoConsulta['id_usuario']];
            }else{
            return ['estado' => 'error',
                    'codigo' => 1,
                    'descripcion' => 'error al iniciar sesion'];
            }
        } catch (PDOException $e) {
            echo("Error al Iniciar Session: ".$e);
        }
    }

    public function validarToken($token_sin_validar, $id_usuario){
        // $resultado = $this->db->
    }

    public function registrarNuevo($datos_registro){
        
        if(!$this->existeUsuario($datos_registro['id_usuario'])){
            return $this->db->insert("usuario", $datos_registro);
        }else {
            return ['estado' => 'error',
                    'codigo' => 1,
                    'descripcion' => 'el usuario ya existe en la base'];
        }
    }

    public function listadoUsuarios(){
        return $this->db->selectAll('usuario');
    }
}


?>