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
            $juegoModel = new JuegoModel();
            // echo("{$_SESSION['id_usuario']} {$_GET['partida']}");
            $datosPartida = $juegoModel->traerDatosPartida([
                'id_usuario' => $_SESSION['id_usuario'], 
                'id_partida' => $_GET['partida']
            ]);

            // echo("<pre>");
            // var_dump($datosPartida['piezas'][0]['div-id']);

            return view('partida_iniciada', [
                ...$datosPartida,
                ...$sesion->cargarPanelNavegacion()
            ]);
            
        }

    } // FIN metodo : continuar_partida()


    public function guardar_imagen(){

        echo("guardar_imagen*******");
        // echo("<pre>");
        // var_dump($_POST);

        // $dataURI = $_POST['dataURI']; // Obtengo el dataURI de la solicitud POST
        // $carpeta_destino = realpath("imagenes_partida");

        // // Verifico si el dataURI está en el formato correcto
        // if (preg_match('/^data:image\/(\w+);base64,/', $dataURI, $matches)) {
        //     $tipoImagen = $matches[1]; // Obtengo el tipo de imagen (por ejemplo, 'jpeg', 'png', etc.)
        //     $contenidoImagen = substr($dataURI, strpos($dataURI, ',') + 1); // Obtengo el contenido de la imagen codificado en base64

        //     // Decodifico el contenido de la imagen
        //     $imagenDecodificada = base64_decode($contenidoImagen);

        //     if ($imagenDecodificada !== false) {
        //         // Genero un nombre de archivo único
        //         $nombreArchivo = uniqid() . '.' . $tipoImagen;

        //         // Ruta completa del archivo en la carpeta de destino
        //         $rutaCompleta = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;

        //         // Guardo la imagen en la carpeta de destino
        //         if (file_put_contents($rutaCompleta, $imagenDecodificada) !== false) {
        //             return $nombreArchivo; // Devuelve el nombre del archivo guardado
        //             echo 'Error al guardar la imagen en el servidor.';
        //             return false; // Error al guardar la imagen
        //         }
        //     } else {
        //         echo 'Error al decodificar la imagen.';
        //         return false; // Error al decodificar la imagen
        //     }
        // } else {
        //     echo 'DataURI no válido.';
        //     return false; // DataURI no válido
        // }
    }

} // FIN clase : juegoController
