<?php
namespace App\models;

use App\Core\Model;
use PDO;
use PDOException;

class UsuarioModel extends Model{

    public $dsn;
    public $db;
    public $params;
    private $id_usuario;

    public function setIdUsuario($id_usuario){$this->id_usuario=$id_usuario;}
    public function getIdUsuario(){return $this->id_usuario;}

    public function existeUsuario($id_usuario){
        $resultado = $this->db->selectOne('usuario','id_usuario',$id_usuario);        
        if($resultado['resultado']) {
            $this->setIdUsuario($id_usuario);
            return true;
        }else{
            return false;
        }  
    }   

    public function contraseniaCorrecta($contrasenia){
        $resultado = $this->db->existOne('usuario',$this->getIdUsuario(), $contrasenia);
        return $resultado['resultado'] ? true : false;
    }


    public function buscarUsuario($id_usuario){
        $sql = "SELECT 1 FROM usuario WHERE `id_usuario` =:id_usuario;";   
        $array_consulta = [':id_usuario'=>$id_usuario];
        try {
            $statement = $this->db->prepare($sql);
            $statement->execute($array_consulta);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            if($statement->rowCount()>0){
                return ['ya_existe'=> true];
            }else{
                return ['ya_existe'=>false];
            }
            // foreach($statement as $res){
            //     var_dump($res);
            // }
        } catch (\Throwable $th) {

        }
    }

    public function iniciarSession($usuario, $contrasenia){

        $sql = 'SELECT * FROM usuario WHERE `id_usuario` = :id_usuario AND `contrasenia` = :contrasenia';
        $array_consulta = [':id_usuario' => $usuario, ':contrasenia' => $contrasenia];

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute($array_consulta);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            foreach($statement as $res){
                $result[] = $res;
            }

            if(!empty($result)){
                return [
                    'logueo' => true,
                    'contrasenia_correcta' => true,
                    'id_usuario' => $result[0]['id_usuario']];
            }else{
                return [
                    'logueo' => false,
                    'contrasenia_correcta' => false,
                    'id_usuario' => []];
            }
        } catch (PDOException $e) {
            echo("Error al Iniciar Session: ".$e);
        }

    }
    public function registrarUsuario($datos_registro){
        var_dump($datos_registro);
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