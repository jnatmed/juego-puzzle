<?php
namespace App\models;

use App\Core\Model;

class UsuarioModel extends Model{

    protected $table = 'usuario';

    public function iniciarSession($usuario, $contrasenia){
        
        try {
            $result = $this->db->selectId($this->table, $usuario, $contrasenia);
            var_dump($result);
            // return ['registrado' => $registrado,
            // 'contrasenia_correcta' => $contrasenia_correcta,
            // 'id_usuario'=> $id_usuario];

        } catch (\Throwable $th) {
            echo("Error: ".$th);
        }

    }
}


?>