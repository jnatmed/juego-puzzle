<?php
namespace App\controllers;

use \App\models\JuegoModel;
use \App\controllers\SessionController;
use \App\controllers\MashupController;
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

        // $rankingJugador = $juegoModel->getRankingJugador($_SESSION['id_usuario']);

        $listado = [
            'ranking_jugadores' => [['id_usuario' => 'juan', 'alias' => 'juanman','puntaje' => 10]],
            // 'ranking_jugadores' => $rankingJugadores,
            ...$sesion->cargarPanelNavegacion()
        ];

        return view('ranking', $listado);
    }        
    
    public function new(){

        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    

        $sesion = new SessionController();

        // pregunto si tiene sesion iniciada. 
        $resultado = $sesion->tieneSesionActiva();
        
        echo "metodo new: tienesesionactiva? </br>";
        echo("<pre>");
        var_dump($resultado);

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

            // $datos['']
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
            
            $datosPartida = $juegoModel->traerDatosPartida([
                'id_usuario' => $_SESSION['id_usuario'], 
                'id_partida' => $_GET['partida']
            ]);

            return view('partida_iniciada', [
                ...$datosPartida,
                ...$sesion->cargarPanelNavegacion()
            ]);
            
        }

    } // FIN metodo : continuar_partida()


    public function guardar_imagen(){

        $jsonData = json_decode(file_get_contents("php://input"), true);

        $sesion = New SessionController();

        $respuesta = $sesion->tieneSesionActiva();
        
        if($respuesta['estado'] === 'ok' ){

            $dataURI = $jsonData['dataURI']; // Obtengo el dataURI de la solicitud POST

            echo("<pre>");
            var_dump($dataURI);
            
            $id_partida = $jsonData['id_partida'];
            $id_usuario = $jsonData['id_usuario'];
            $carpeta_destino = realpath("imagenes_partida");
    
            // Verifico si el dataURI está en el formato correcto
            if (preg_match('/^data:image\/(\w+);base64,/', $dataURI, $matches)) {
                $tipoImagen = $matches[1]; // Obtengo el tipo de imagen (por ejemplo, 'jpeg', 'png', etc.)
                $contenidoImagen = substr($dataURI, strpos($dataURI, ',') + 1); // Obtengo el contenido de la imagen codificado en base64
    
                // Decodifico el contenido de la imagen
                $imagenDecodificada = base64_decode($contenidoImagen);
    
                if ($imagenDecodificada !== false) {
                    // Genero un nombre de archivo único
                    $nombreArchivo = hash('sha256', $id_usuario . $id_partida); 
    
                    // Ruta completa del archivo en la carpeta de destino
                    $rutaCompleta = $carpeta_destino . DIRECTORY_SEPARATOR . $nombreArchivo . '.' . $tipoImagen;
    
                    // Guardo la imagen en la carpeta de destino
                    if (file_put_contents($rutaCompleta, $imagenDecodificada) !== false) {
                        return json_encode([
                            'estado' => 'ok',
                            'nombreArchivo' => $nombreArchivo]); // Devuelve el nombre del archivo guardado
                    }else {   
                        return json_encode(['estado' => 'error',
                        'descripcion' => 'Error al guardar la imagen en el servidor.']);
                    }
                } else {
                    return json_encode(['estado' => 'error',
                    'descripcion' => 'Error al decodificar la imagen.']);                
                }
            } else {
                return json_encode(['estado' => 'error',
                'descripcion' => 'DataURI no válido.']);                
            }
        } else {
            return json_encode(['estado' => 'error',
            'descripcion' => 'Sesion no iniciada.']);                
        }       
    } // FIN metodo : guardar_imagen()

    public function mashup(){
        

    }

} // FIN clase : juegoController
