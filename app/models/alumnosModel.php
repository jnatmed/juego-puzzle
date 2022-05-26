<?php
namespace App\models;

use App\Core\Model;
use PDO;

class AlumnosModel extends Model{

    public function cargarAlumnos(){
        return $this->db->selectAll('alumno');
    }

    public function verAlumno($nombre_alumno){
        return $this->db->selectOne('alumno','nombre_alumno',$nombre_alumno);
    }

}