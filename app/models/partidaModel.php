<?php
namespace App\models;

use App\Core\Model;
use PDO;

class PartidaModel{

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

    /**
     * trae imagenes que usuarios clasificaron como publicas
     * para que otros las puedan ver y usar
     */
    public function cargarImagenesPublicas(){

    }

    /**
     * se usa una vez el usuario ya logueado selecciono la partida
     */
    public function verDetallePartidaUsuario($id_usuario, $id_partida){

    }

    /**
     * trae las partidas de un usuario
     */
    public function cargarListadoPartidas($id_usuario){

        $sql = 'SELECT * FROM partida WHERE `id_usuario` = :id_usuario';
        $array_consulta = [':id_usuario' => $id_usuario];

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute($array_consulta);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            foreach($statement as $res){
                $result[] = $res;
            }
            if(!empty($result)){
                return $result[0];
            }else{
                return [];
            }
            return $result[0];

        } catch (\Throwable $th) {
            echo("Error : ".$th);
        }


    }

    public function nuevaPartida($id_usuario, $imagen, $dificultad){

        

    }

    public function jugarPartida($id_usuario, $id_partida){
        
    }

}


?>