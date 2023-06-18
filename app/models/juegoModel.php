<?php
namespace App\models;

use App\Core\Model;
use App\Core\App;
use PDO;

class JuegoModel extends Model{

    public $log;

    public function addEstado($parameters){
        $log = App::get('logger');
        $sql = "INSERT INTO partida(id_usuario, estados_del_juego) VALUES(".implode(",", array_values($parameters)).")";
        $log->info($sql);
        try {
            $statement = $db->prepare($sql);
            foreach(array_keys($parameters) as $param){
                $statement->bindValue(":$param", $parameters[$param], PDO::PARAM_STR);
            }            
            $statement->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
            $log->info($e->getMessage());
        }
    }

}
