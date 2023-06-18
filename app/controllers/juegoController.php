<?php
namespace App\controllers;

use \App\models\JuegoModel;
use \App\controllers\SessionController;
use App\Core\App;

class JuegoController extends JuegoModel{

    public function new(){
        $sesion = new SessionController();
        // return $sesion->login();
        return view('nuevo_juego');
    }

    public function guardarEstado($estado){
        $juegoModel = new JuegoModel();
        $juegoModel->addEstado($estado);
    }    


    

    public function reciboEstado(){
        $log = App::get('logger');
        
        $_post = json_decode(file_get_contents('php://input'),true);

        //este es un array
        if(isset($_post['estado_puzzle'])){
        
            $log->info("insertando estado_puzzle");
            // $log->info("\$_post['estado_puzzle'] ".$_post['estado_puzzle']);
            $array_puzzle = '['.implode(",", $_post["estado_puzzle"]).']';
            $log->info('method reciboEstado()'.$array_puzzle);
            $this->guardarEstado([
                "id_usuario" => 1,
                "estados_del_juego" => $array_puzzle
            ]);
            $log->info("puzzle : INSERTADO CON EXITO");
        }
        if(isset($_post['estado_piezas'])){
        
            $log->info("insertando estado_piezas");
            $estado_piezas = implode(",", $_post["estado_piezas"]);
            $log->info($estado_piezas);
            $this->guardarEstado([
                "id_usuario" => "1",
                "estados_del_juego" => $estado_piezas
            ]);
            $log->info("pieza : INSERTADO CON EXITO");

        }

    }
}
