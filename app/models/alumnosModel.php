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

    public function cargarRecibos(){
        return $this->db->selectAll('recibos');
    }

    public function cargarPadres(){
        return $this->db->selectAll('padre');
    }

    public function guardarAlumno($parameters){
        return $this->db->insert('alumno',$parameters);
    }
    public function guardarPadre($parameters){
        return $this->db->insert('padre',$parameters);
    }
    public function guardarRecibo($parameters){
        return $this->db->insert('recibos',$parameters);
    }
}