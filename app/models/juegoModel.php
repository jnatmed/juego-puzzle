<?php
namespace App\models;

use App\Core\Model;
use App\Core\App;
// use PDO;
// use PDOException;

class JuegoModel extends Model{

    public $log;

    public function addEstado($parameters){
        $log = App::get('logger');

        $resultado = $this->db->insertEstado($parameters);

        $log->info("3) {$resultado['registro_exitoso']}", ['resultado del registro' , 'juegoModel']);
    }

}
