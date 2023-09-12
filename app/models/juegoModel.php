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


    function crearFragmentos($nombreImagen) {
        // Verifica si la imagen existe
        $pathImg = realpath("imagenes_partida") . DIRECTORY_SEPARATOR  . $nombreImagen .".jpeg";
        // echo("<pre>");
        // var_dump($pathImg);
        if (!file_exists($pathImg)) {
            return ['estado' => 'error', 'descripcion' => 'Imagen no encontrada'];
        }
    
        // Cargar la imagen original utilizando GD
        $imagen = imagecreatefromjpeg($pathImg);
    
        // Obtener el ancho y alto de la imagen original
        $anchoOriginal = imagesx($imagen);
        $altoOriginal = imagesy($imagen);
    
        // Tamaño de los fragmentos
        $tamanoFragmento = 100;

        // Ajusta la imagen original a un tamaño de 300x300
        $nuevaImagen = imagecreatetruecolor(300, 300);
        imagecopyresampled(
            $nuevaImagen,    // Lienzo de destino
            $imagen,         // Imagen original
            0, 0,            // Coordenadas del fragmento en el lienzo
            0, 0,            // Coordenadas del fragmento en la imagen original
            300, 300,        // Tamaño del fragmento
            $anchoOriginal, $altoOriginal   // Tamaño de destino (manteniendo la proporción)
        );

        // Arreglo para almacenar los fragmentos
        $fragmentos = array();

        // Itera para dividir la imagen en fragmentos de 100x100
        for ($fila = 0; $fila < 3; $fila++) {
            for ($columna = 0; $columna < 3; $columna++) {
                // Crea un lienzo (canvas) para el fragmento
                $fragmento = imagecreatetruecolor($tamanoFragmento, $tamanoFragmento);
    
                // Copia el fragmento de la imagen original al lienzo
                imagecopyresampled(
                    $fragmento,         // Lienzo de destino
                    $nuevaImagen,            // Imagen original
                    0, 0,               // Coordenadas del fragmento en el lienzo
                    $columna * $tamanoFragmento, $fila * $tamanoFragmento,  // Coordenadas del fragmento en la imagen original
                    $tamanoFragmento, $tamanoFragmento,  // Tamaño del fragmento
                    $tamanoFragmento, $tamanoFragmento   // Tamaño de destino
                );
    
            // Guarda el fragmento en un búfer en formato JPEG
            ob_start();
            imagejpeg($fragmento, null, 100);
            $fragmentoBase64 = base64_encode(ob_get_contents());
            ob_end_clean();

            // Almacena el fragmento en el arreglo
            $fragmentos[] = $fragmentoBase64;

            // Libera la memoria del fragmento
            imagedestroy($fragmento);
            }
        }
    
        // Libera la memoria de la imagen original
        imagedestroy($imagen);
    
        return ['estado' => 'ok', 'fragmentos' => $fragmentos];
    }    

    public function traerDatosPartida($data) {

        $resultado = $this->db->traerUltimoEstadoPartida($data);

        $nombreImagen = hash('sha256', $data['id_usuario'] . $data['id_partida']);
        $respuesta = $this->crearFragmentos($nombreImagen);

        if($respuesta['estado'] == 'ok') {
            $resultado['fragmentos'] = $respuesta['fragmentos'];
        }   

        // echo("<pre>");
        // var_dump($respuesta);

        if($resultado['estado'] == 'ok'){
            return ['estado' => 'ok',
                    ...$resultado['dato'],
                    ...['fragmentos' => $resultado['fragmentos']],
                    'descripcion' => '1 - metodo traerDatosPartida: obtencion de datos exitoso'];
        } else {
            // Manejo si no se encuentran datos en la base de datos
            return ['estado' => 'error',
                    'descripcion' => '1 - metodo traerDatosPartida: error trayendo los datos de la db'];
        }

    }

}
