<?php
namespace App\controllers;

use \App\models\JuegoModel;
use \App\controllers\SessionController;
use App\Core\App;

class JuegoController extends JuegoModel{

    public $log;
    private $id_partida;

    public function __construct(){
        $this->log = App::get('logger');
    }

    public function ranking(){

        $sesion = new SessionController();

        $panel = $sesion->cargarPanelNavegacion();

        $juegoModel = new JuegoModel();

        $rankingJugador = $juegoModel->getRankingJugador($_SESSION['id_usuario']);

        $listado = [
            'ranking_jugadores' => [['id_usuario' => 'juan', 'alias' => 'juanman','puntaje' => 10]],
            // 'ranking_jugadores' => $rankingJugadores,
            'enlaces' => $panel['enlaces'],
            'username' => $panel['username'],
            'tipo_usuario' => $panel['tipo_usuario']
        ];

        return view('ranking', $listado);
    }        
    

    public function new(){
        $sesion = new SessionController();
        return view('nuevo_juego', $sesion->cargarPanelNavegacion());
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
        
        $this->log->info("insertando {$type}");
        $array = '['.implode(",", $param).']';
        $this->log->info("method reciboEstado() {$array}");
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
        
        $sesion = New SessionController();

        $respuesta = $sesion->tieneSesionActiva();
        
        if($respuesta['estado'] == 200 ){
            
            $id_usuario = $sesion->getIdUsuario();
            $matriz = $sesion->getMatriz();
            $id_partida = $sesion->getIdPartida();

            $this->log->info(`success : {$id_usuario}`);

            return(json_encode(
                            ["status" => "success",
                             "data" => ["id_sesion" => $id_usuario,
                            "matriz" => $matriz,
                            "id_partida" => $id_partida]
                        ]));

        }else{

            $this->log->info(`error : error`);
            
            return(json_encode(
                            ["status" => "error",
                             "data" => []
                        ]));
        }

        // $_post = json_decode(file_get_contents('php://input'),true);

        //recibo el array asociativo
        // if(isset($_post['estado_puzzle'])){
        
        //     $this->guardar('estado_puzzle', $_post['estado_puzzle']);

        //     $this->log->info("puzzle : INSERTADO CON EXITO");
        //     return(json_encode(['pieza' => 'INSERTADO CON EXITO',
        //                         "id_usuario" => "1" ]));            
        // }
        // if(isset($_post['estado_piezas'])){
            
        //     $this->guardar('estado_piezas', $_post['estado_piezas']);        

        //     $this->log->info("pieza : INSERTADO CON EXITO");
        //     return(json_encode(['pieza' => 'INSERTADO CON EXITO',
        //                         "id_usuario" => "1" ]));
        // }

    }
}
