<?php
namespace App\models;

use App\Core\Model;


class PartidaModel extends Model{
    

    protected $table = 'partida';
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

    }

    public function nuevaPartida($id_usuario, $imagen, $dificultad){

    }

    public function jugarPartida($id_usuario, $id_partida){
        
    }

}


?>