<?php
namespace App\models;

use App\Core\Model;
use PDO;
use PDOException;

class UsuarioModel extends Model{
    public $db;
    private $id_usuario;
    private $tipo_usuario;

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
                        'tipo_usuario' => $resultadoConsulta['tipo_usuario'],
                        'contrasenia' => $resultadoConsulta['contrasenia']
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

    public function registrarUsuario($datos_registro){

        // var_dump($datos_registro);
        $consulta = "INSERT INTO `usuario`(`id_usuario`,
                                           `contrasenia`,
                                           `alias`,
                                           `email` ) VALUES (
                                                            :id_usuario,
                                                            :contrasenia,
                                                            :alias,
                                                            :email)";
        $array_consulta = [
            ':id_consulta' => $datos_registro['id_usuario'],
            ':contrasenia' => $datos_registro['contrasenia'],
            ':alias' => $datos_registro['alias'],
            ':email' => $datos_registro['email']
        ];
        
        try {
            $statement = $this->db->prepare($consulta);
            $statement->execute($array_consulta); 
            
            /**
             * comprobar, si insertó correctamente, devolver true
             * sino false
             */
            if($statement->rowCount()>0){
                return ['registro_exitoso'=> true];
            }else{
                return ['registro_exitoso'=>false];
            }
        } catch (PDOException $e) {
            echo("Error al registrar Usuario: ".$e);
        }
    }
}


?>