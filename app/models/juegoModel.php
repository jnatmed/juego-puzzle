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
                
        $log->info($this->db->insertEstado($parameters));
        
    }

}
