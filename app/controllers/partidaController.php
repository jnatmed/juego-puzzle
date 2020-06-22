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
        return view('juego-puzzle', array('piezas' => $piezas, 'imagen_original' => $img_jpeg));    
    }

    public function mostrarImagenes(){

        $info = require('info.php');
        $imagenes = $info['infoImg'];
        $imgMatriz = $info['matriz'];
        $listadoImg = [];
        $imgC = new ImgController($imagenes['dimensiones']);
        foreach ($imagenes['imgs'] as $nombre_img=>$path_img){

            $img_resultado = base64_encode(file_get_contents($path_img));
    
            $listadoImg[$nombre_img] = $img_resultado;
        }
        // var_dump($listadoImg);
        // exit();
        return view('listadeImagenes',array('listado' => $listadoImg));

    }

    public function calcularNuevoAnchoyAlto($anchopagina, $dificultad){
        while(($anchoPagina % $dificultad)<>0){
            $anchoPagina--;
        }
        return $anchoPagina;
    }

    public function crearArreglo($ancho, $tamanioPieza, $dificultad){
        $rango = [];
        $cont = 0;
        $i = 0;
        while($cont < $ancho){
            if($cont==0){
                $rango[i]=['x'=>$cont, 'y'=>$cont+$tamanioPieza];
            }else{
                $rango[i]=['x'=>$cont+1, 'y'=>$cont+$tamanioPieza];
            }
            $cont+=$tamanioPieza;
            $i++;
        }
        return $rango;
    }

    public function cargarPuzzle(){
        $path = 'public\imgs\/';      
        $id_imagen = $_GET['id_imagen'];
        $anchoPagina = $_GET['ancho_pagina'];
        
        $dificultad = 3; 

        $nuevoAncho = calcularNuevoAnchoyAlto($anchoPagina, $dificultad);
        $rango = crearArreglo($nuevoAncho, $nuevoAncho / $dificultad, $dificultad);
        $combinaciones = armarCombinaciones();
        
        $array = [
            'ancho_canvas' => $anchoPagina,
            'alto_canvas' => $anchoPagina,
            'piezas' => $piezas,
            'imagen_original' =>$img_jpeg
        ];
        view('juego-puzzle', $array);
    }

    public function cargarArreglo(){
        /**
         * IN: 
         * OUT: arreglo con estado inicial del juego
         */
    }

    public function login(){
        include 'app/views/login.html';
        // view('login');
    }

}

?>

