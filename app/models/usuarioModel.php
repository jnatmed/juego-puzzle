<?php
namespace App\models;

use App\Core\Model;
use PDO;

class UsuarioModel{

    public $dsn;
    public $db;
    public $params;

    public function __construct(){
        $this->params = require 'config.php';        
        $this->dsn = sprintf("%s;dbname=%s", $this->params['database']['connection'], $this->params['database']['name']);
        try{
            $this->db = new PDO($this->dsn, $this->params['database']['username'],$this->params['database']['password']);    
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);            
        }catch(\Throwable $th){
            echo ("<pre>");
            var_dump($th);
            exit(0);   
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
            return [
                'registrado' => true,
                'contrasenia_correcta' => true,
                'id_usuario' => $result[0]['id_usuario']
            ];
        } catch (\Throwable $th) {
            echo("Error : ".$th);
        }

    }
    public function registrarUsuario($datos_registro){
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
            $sql = $this->db->prepare($consulta);
            $sql->execute($array_consulta);    
        } catch (\Throwable $th) {
            echo($th);
        }
    }
}


?>