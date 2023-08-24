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

    public function traerPartidas($id_usuario){

        $resultado = $this->db->selectAllByUser($id_usuario);

        // echo("<pre>");
        // var_dump($resultado['listado']);

        if($resultado['estado'] == 'ok') {
            return [
                'estado' => 'ok',
                'listado' => $resultado['listado'],
                'descripcion' => 'juegoModel : inserción correcta'
            ];
        } else {
            return [
                'estado' => 'error',
                'descripcion' => 'juegoModel : error al traer partidas'
            ];
        }
    }

    public function traerUltimoIdPartida($id_usuario){
        $resultado = $this->db->traerUltima($id_usuario);
        return $resultado;
    }

    public function crearNuevaPartida($id_usuario, $nuevoIdPartida){

        $datosJuego = [
            'id_partida' => $nuevoIdPartida,
            'id_usuario' => $id_usuario,
            'estado_partida' => NULL,
            'progreso' => 'iniciado'
        ];

        // echo("<pre>");
        // var_dump($datosJuego);

        $resultado = $this->db->insert('partida', $datosJuego);

        if ($resultado['estado'] == 'ok') {
            return [
                'estado' => 'ok',
                'descripcion' => 'creado correctamente'
            ];
        }else{
            return [
                'estado' => 'error',
                'descripcion' => 'error al crear partida'
            ];
        }
    }

    public function actualizarPartida($datos){
        return $this->db->updatePartida($datos);
    }

}
