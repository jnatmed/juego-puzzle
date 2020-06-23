<?php
namespace App\controllers;

use \App\controllers\ImgController;

class PartidaController{
    public $movPermitidos;
    public $matriz;
    public $combinaciones;
    public $rango;

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
        $listadoImg = [];
        /**
         * CARGO IMAGENES GUARDADAS EN EL DIRECTORIO LOCAL
         */
        foreach ($imagenes['imgs'] as $nombre_img=>$path_img){
            $img_resultado = base64_encode(file_get_contents($path_img));
            $listadoImg[$nombre_img] = $img_resultado;
        }
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

    public function cargarMovimientoPermitidos(){
        $cantElementos = 9;
        $dificultad = 3;
        $permitidos = [];
        for ($i=1; $i < $cantElementos; $i++) { 
            for ($j=1; $j < $cantElementos; $j++) { 
                if($i!=$j){
                    echo("RESTA ABS: ${i} - ${j} : ".abs($i-$j)."<br>");
                    if(abs($i-$j)==1||(abs($i-$j)==$dificultad)){
                        echo("guardo : ${j}");
                        $permitidos[$i][]=$j;
                        var_dump($permitidos[$i]);
                    }
                }
            }
        }
        return $permitidos;
    }

    public function cargarPuzzle(){
        $info = require('info.php');
        $path = 'public\imgs';      
        $id_imagen = $_GET['id_imagen'];
        $anchoPagina = $_GET['ancho_pagina'];
        $this->movPermitidos = $info['mov-permitidos'];


        $dificultad = 5; 

        // echo("ancho pagina: ".$anchoPagina); 
        if($anchoPagina<450){
            $anchoPagina = $anchoPagina - 50;
            // echo("ancho menor a 450<br> es de: ".$anchoPagina);
            $nuevoAncho = $this->calcularNuevoAnchoyAlto($anchoPagina, $dificultad);
            // echo("nuevo valor: ".$anchoPagina);
        }else{
            $anchoPagina = 450;
            $nuevoAncho = $this->calcularNuevoAnchoyAlto($anchoPagina, $dificultad);
        }
        
        $this->rango = $this->crearArreglo($nuevoAncho, $nuevoAncho / $dificultad, $dificultad);
        $this->combinaciones = $this->armarCombinaciones($this->rango);

        $imgC = new ImgController(['ANCHO'=>$nuevoAncho, 
                                   'ALTO'=>$nuevoAncho,
                                   'TAMANIO_PIEZA'=>$nuevoAncho / $dificultad]);

        
        $salida = $imgC->redimensionar($info['infoImg']['imgs'][$id_imagen]);

        $img_jpeg = $salida['formato_jpeg'];
        $this->matriz = $this->armarMatriz($nuevoAncho, $nuevoAncho/$dificultad, $dificultad);
        foreach ($this->matriz as $coord) {
            $resul = $imgC->recortar(NULL, $coord['x'], $coord['y'],$img_jpeg);            
            $piezas[] = $resul['resultado'];
        }
        
        // echo("<pre>");
        $array = [
            'ancho_canvas' => $nuevoAncho,
            'alto_canvas' => $nuevoAncho,
            'piezas' => $piezas,
            'imagen_original' =>$img_jpeg,
            'tamanio_pieza'=> ($nuevoAncho / $dificultad),
            'cantElementos' => ($dificultad * $dificultad),
            'dificultad' => $dificultad,
            'movPermitidos' => json_encode($this->movPermitidos[$dificultad]),
            'matriz' => json_encode($this->rango),
            'combinaciones' => json_encode($this->combinaciones)
        ];
        // var_dump($array['mov-permitidos']);
        return view('juego-puzzle', $array);
    }

    public function cargarMovimientosPermitidos(){
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

