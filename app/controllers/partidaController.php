<?php
namespace App\controllers;

use \App\controllers\ImgController;

class PartidaController{

    public function home(){

        $info = require('info.php');
        $imagenes = $info['infoImg'];
        $imgMatriz = $info['matriz'];

        // echo("<pre>");
        // var_dump($info);
        // exit();
        $imgC = new ImgController($imagenes['dimensiones']);

        foreach ($imagenes['imgs'] as $nombre_img=>$path_img){
            /**
             * redimensiono la imagen segun la configuracion elegida, en info.php
             * y puesta en $imagenes['dimensiones']
             */
            $salida = $imgC->redimensionar($path_img);
            $img_jpeg = $salida['formato_jpeg'];
            foreach ($imgMatriz as $coord){
                /**
                 * aca una vez redimensionada la imagen a un tamaño manejable, 
                 * empiezo a extraer las piezas segun la configuracion elegida en $info['matriz']
                 */
                $resul = $imgC->recortar(NULL, $coord['x'], $coord['y'],$img_jpeg);            
                /**
                 * y guardo pieza por pieza en el arreglo que voy a mostrar
                 */
                $piezas[] = $resul['resultado'];
                } 
        }
        return view('juego-puzzle', array('piezas' => $piezas));    
    }

    public function cargarMovimiento(){
        /**
         * IN: id_usuario, id_partida, movimiento, timeStamp
         * OUT: movimiento_exitoso, movimiento_denegado, estado_objetivo_alcanzado
         */
        var_dump($_POST);
    }

    public function cargarArreglo(){
        /**
         * IN: 
         * OUT: arreglo con estado inicial del juego
         */
    }

}

?>

