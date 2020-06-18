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
            $salida = $imgC->redimensionar($path_img);
            $img_jpeg = $salida['formato_jpeg'];
            foreach ($imgMatriz as $coord){
                $resul = $imgC->recortar(NULL, $coord['x'], $coord['y'],$img_jpeg);            
                $piezas[] = $resul['resultado'];
                } 
        }
        // var_dump($piezas);
        // exit();
        include 'app/views/juego-puzzle.php';
        // return view('juego-puzzle', $piezas);    

    }

    public function mostrar(){
        var_dump($_REQUEST);
        echo("hola");
    }

}

?>

