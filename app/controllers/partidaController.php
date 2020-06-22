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
            $nombre[] = explode('public\\imgs\\',$path_img);
            // echo("<pre>");
            // var_dump($nombre);
            // $listadoImg[$nombre[1]] = $img_resultado;
            $listadoImg[$nombre_img] = $img_resultado;
        }
        // var_dump($listadoImg);
        // exit();
        return view('listadeImagenes',array('listado' => $listadoImg));

    }

    public function calcularNuevoAnchoyAlto($anchopagina, $dificultad){
        while(($anchopagina % $dificultad)<>0){
            $anchopagina--;
        }
        return $anchopagina;
    }

    public function crearArreglo($ancho, $tamanioPieza, $dificultad){
        $rango = [];
        $cont = 0;
        $i = 0;
        while($cont < $ancho){
            if($cont==0){
                $rango[$i]=['x'=>$cont, 'y'=>$cont+$tamanioPieza];
            }else{
                $rango[$i]=['x'=>$cont+1, 'y'=>$cont+$tamanioPieza];
            }
            $cont+=$tamanioPieza;
            $i++;
        }
        return $rango;
    }

    public function armarCombinaciones($rangos){
        $cont = 1;$combos=[];
        for ($i=0; $i < count($rangos) ; $i++) { 
            $coord = $rangos[$i];
            for ($j=0; $j < count($rangos) ; $j++) { 
                $combos['('.$rangos[$j]['x'].','.$rangos[$j]['y'].'):('.$rangos[$i]['x'].','.$rangos[$i]['y'].')'] = $cont;
                $cont++;
            }
        }
        return $combos;
    }

    public function armarMatriz($ancho, $tamanioPieza, $dificultad){
        $i=0;
        $array=[];
        $matriz=[];
        while($i <= ($ancho-$tamanioPieza)){
            $array[]=$i;
            $i+=$tamanioPieza;
        }
        // var_dump($array);
        for ($i=0; $i <= $dificultad-1 ; $i++) { 
            for ($j=0; $j <= $dificultad-1 ; $j++) { 
                $matriz[]=['x'=>$array[$j], 
                           'y'=>$array[$i]
                        ];
            }
        }
        return $matriz;
    }

    public function cargarPuzzle(){
        $info = require('info.php');
        $path = 'public\imgs';      
        $id_imagen = $_GET['id_imagen'];
        $anchoPagina = $_GET['ancho_pagina'];
    

        $dificultad = 3; 
        // echo("<pre>");
        // var_dump($path);
        // var_dump($id_imagen);
        // var_dump($anchoPagina);
        // var_dump($dificultad);

        if($anchoPagina<450){
            $nuevoAncho = $this->calcularNuevoAnchoyAlto($anchoPagina, $dificultad);
        }else{
            $nuevoAncho = 450;
        }
        
        $rango = $this->crearArreglo($nuevoAncho, $nuevoAncho / $dificultad, $dificultad);
        $combinaciones = $this->armarCombinaciones($rango);

        $imgC = new ImgController(['ANCHO'=>$nuevoAncho, 
                                    'ALTO'=>$nuevoAncho,
                                    'TAMANIO_PIEZA'=>$nuevoAncho / $dificultad]);

        
        $salida = $imgC->redimensionar($info['infoImg']['imgs'][$id_imagen]);

        $img_jpeg = $salida['formato_jpeg'];
        $matriz = $this->armarMatriz($nuevoAncho, $nuevoAncho/$dificultad, $dificultad);
        foreach ($matriz as $coord) {
            $resul = $imgC->recortar(NULL, $coord['x'], $coord['y'],$img_jpeg);            
            $piezas[] = $resul['resultado'];
        }

        $array = [
            'ancho_canvas' => $nuevoAncho,
            'alto_canvas' => $nuevoAncho,
            // 'tamanio_pieza'=> ($nuevoAncho / $dificultad),
            'piezas' => $piezas,
            'imagen_original' =>$img_jpeg,
            // 'matriz' => $matriz
        ];
        // var_dump($array);
        
        return view('juego-puzzle', $array);
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

