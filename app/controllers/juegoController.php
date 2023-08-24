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

        $juegoModel = new JuegoModel();

        $rankingJugador = $juegoModel->getRankingJugador($_SESSION['id_usuario']);

        $listado = [
            'ranking_jugadores' => [['id_usuario' => 'juan', 'alias' => 'juanman','puntaje' => 10]],
            // 'ranking_jugadores' => $rankingJugadores,
            ...$sesion->cargarPanelNavegacion()
        ];

        return view('ranking', $listado);
    }        
    

    public function new(){
        $sesion = new SessionController();

        // pregunto si tiene sesion iniciada. 
        $resultado = $sesion->tieneSesionActiva();
        
        if ($resultado['estado'] == 'ok') {

            $juegoModel = new JuegoModel();
            $nuevoIdPartida = $juegoModel->traerUltimoIdPartida($_SESSION['id_usuario']) + 1;
            $resultado = $juegoModel->crearNuevaPartida($_SESSION['id_usuario'], $nuevoIdPartida);
            // echo("<pre>");
            // var_dump($resultado);
            if($resultado['estado'] == 'ok'){
                return view('nuevo_juego', [
                    'id_partida' => $nuevoIdPartida,
                    ...$sesion->cargarPanelNavegacion()
                ]);
            }else{
                echo("<pre>");
                var_dump($resultado);
            }
        }
        return view('nuevo_juego', $sesion->cargarPanelNavegacion());
    }

    public function listado_partidas(){

        $sesion = New SessionController();

        $respuesta = $sesion->tieneSesionActiva();
        
        if($respuesta['estado'] == 'ok' ){

            $juegoModel = new JuegoModel();

            $listado = $juegoModel->traerPartidas($_SESSION['id_usuario']);

            // echo("<pre>");
            // var_dump($listado);

            if ($listado['estado'] == 'ok') {
                return view('listado_partidas',[
                    'partidas' => $listado['listado'],
                    ...$sesion->cargarPanelNavegacion()
                ]);
            }else{
                return view('listado_partidas',[
                    'partidas' => [],
                    ...$sesion->cargarPanelNavegacion()
                ]);
            }
        }        
    }

    public function reciboEstado(){
        
        $sesion = New SessionController();

        $respuesta = $sesion->tieneSesionActiva();
        
        if($respuesta['estado'] === 'ok' ){

            $jsonData = file_get_contents("php://input");
            
            // Decodificar los datos JSON recibidos
            $datos = json_decode($jsonData, true);

            $juegoModel = new JuegoModel();
            $resultado = $juegoModel->actualizarPartida($datos);

            if($resultado['estado'] == 'ok') {
                echo json_encode(array(
                    'estado' => 'ok',
                    'descripcion' => $resultado['descripcion']
                ));
            }else{
                echo json_encode(array(
                    'estado' => 'error',
                    'descripcion' => $resultado['descripcion']
                ));
            }
        }
    } // FIN metodo : reciboEstado()

    public function continuar_partida(){

        $sesion = New SessionController();

        $respuesta = $sesion->tieneSesionActiva();
        
        if($respuesta['estado'] === 'ok' ){
            /**
             * resultado objetivo
             */
            return view('partida_iniciada',[
                'imagenes' =>[ '0' => 'imagenBase64', '1' => 'imagenBase64' ],
                'piezas' => [ '0' => 'canva_8', '1' => 'canva_2' ],
                'puzzle' => [ '0' => '', '1' => 'canva_1']
            ]);
        }

    } // FIN metodo : continuar_partida()

} // FIN clase : juegoController
