<?php
namespace App\controllers;

use \App\models\JuegoModel;
use \App\controllers\SessionController;
use App\Core\App;

class JuegoController extends JuegoModel{

    public $log;

    public function __construct(){
        $this->log = App::get('logger');
    }

    public function new(){
        $sesion = new SessionController();
        // return $sesion->login();
        return view('nuevo_juego');
    }

    public function guardarEstado($estado){
        $juegoModel = new JuegoModel();
        $juegoModel->addEstado($estado);
    }    
    /*
     * recibo el nombre de la matriz
     * y la matriz
     */
    public function guardar($type, $param){
        
        $array = '['.implode(",", $param).']';
        $this->log->info("1) method reciboEstado() {$array}", ["insertando {$type}"]);
        /*
         * unifico los dos parametros recibidos
         * y los guardo en uno.
         */
        $this->guardarEstado([
            "id_usuario" => 1,
            "estados_del_juego" => implode(",",[
                "nombre_matriz" => $type,
                "matriz" => $array])
        ]);

    }    

    public function reciboEstado(){
        
        $_post = json_decode(file_get_contents('php://input'),true);

        //recibo el array asociativo
        if(isset($_post['estado_puzzle'])){
        
            $this->guardar('estado_puzzle', $_post['estado_puzzle']);

            $this->log->info("4) PUZZLE : INSERTADO CON EXITO" , ['reciboEstado']);
            return(json_encode(['pieza' => 'INSERTADO CON EXITO',
                                "id_usuario" => "1" ]));            
        }
        if(isset($_post['estado_piezas'])){
            
            $this->guardar('estado_piezas', $_post['estado_piezas']);        

            $this->log->info("4) PIEZA : INSERTADO CON EXITO" , ['reciboEstado']);
            return(json_encode(['pieza' => 'INSERTADO CON EXITO',
                                "id_usuario" => "1" ]));
        }

    }
}
