<?php
namespace App\controllers;

use \App\controllers\ImgController;

class PartidaController{
    public $movPermitidos;
    public $matriz;
    public $combinaciones;
    public $rango;
    public $listas_predefinidas;

    public function mostrarImagenes(){        

        $info = require('info_juego.php');
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

    public function mezclar($lista){
        $mezclado = [];
        shuffle($lista);
        foreach ($lista as $item) {
            $mezclado[] = $item;
        }        
        return $mezclado;
    }

    public function buscarVacio($lista, $valor_buscado){
        $encontrado = -1;
        for ($i=0; $i < count($lista)-1; $i++) { 
            if($lista[$i]==$valor_buscado){
                $encontrado = $i;     
            }else{
            }
        }       
        return $encontrado; 
    }

    public function mostrarlista($lista){
        $l = '';
        for ($i=0; $i < count($lista); $i++) { 
            if($i == 0){
                $l = $l."[".$lista[$i].",";
            }elseif($i == count($lista)-1){
                $l = $l.$lista[$i]."]";
            }else{
                $l = $l.$lista[$i].",";
            }
        }
        return $l;
    }

    public function intercambio_valores($pos_anterior, $pos_nueva, $lista){

        $valor_vacio = $lista[$pos_anterior];
        $valor_actual = $lista[$pos_nueva];
        $lista[$pos_nueva] = $valor_vacio;
        $lista[$pos_anterior] = $valor_actual;

        return $lista;
    }

    public function mostrarSublistas($lista){
        foreach ($lista as $sublista) {
            echo("estado posible futuro: ".$this->mostrarlista($sublista)."<br>");
        }
    }

    public function crear_estados_futuros($estado_actual, $mov_permitidos){
        $pos_vacio_actual = $this->buscarVacio($estado_actual, 0);
        $nuevos_estados_futuros = [];
        foreach ($mov_permitidos[$pos_vacio_actual+1] as $nuevos_pos_posible) {
                $nuevos_estados_futuros[] = $this->intercambio_valores($pos_vacio_actual,
                                                                        $nuevos_pos_posible-1,
                                                                        $estado_actual);
        }
        return  $nuevos_estados_futuros;
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
        session_start();
        // if(isset($_SESSION['estado_actual'])){

            $info = require('info_juego.php');
            $path = 'public\imgs';      
    
            $id_imagen = $_GET['id_imagen'];
            $anchoPagina = $_GET['ancho_pagina'];
            $this->movPermitidos = $info['mov-permitidos'];
    
            $dificultad = intval($_GET['dificultad']); 
            $this->listas_predefinidas = $info['listas-predefinidas'][$dificultad];
    
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
            
            $estado_inicial = $this->mezclar($this->listas_predefinidas)[0];
            $estados_futuros = $this->crear_estados_futuros($estado_inicial,
                                                            $this->movPermitidos[$dificultad]);
            $sector_vacio = $this->buscarVacio($estado_inicial, 0);
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
                'combinaciones' => json_encode($this->combinaciones),
    
                'estado_actual' => json_encode($estado_inicial),  
                'estados_futuros' => json_encode($estados_futuros),
                'sector_vacio' => $sector_vacio
            ];
    
            /**
             * 1 - generar el primer estado del juego en `estado_actual`
             * 2 - generar el primer vector de `estados_futuros`
             * 3 - generar el primer `sector_vacio`
             */
            $_SESSION['estados_futuros'] = $estados_futuros;
            $_SESSION['estado_actual'] = $estado_inicial;
            $_SESSION['sector_vacio'] = $sector_vacio;
            /**
             * si inicio session, guardar id_partida, id_usuario
             */
                // $_SESSION['id_partida'] = $dificultad;
                // $_SESSION['id_usuario'] = $dificultad;            
            return view('juego-puzzle', $array);
        // }else{
        //     return ($this->mostrarImagenes());
        // }
    }

    public function cargarMovimientosPermitidos(){
        /**
         * inicio session_start()
         */
        session_start();
        /**
         * mantengo en session (mientras dure) `estados_futuros`,
         *                                     `estado_actual`;
         *                                     `sector_vacio`;
         *                                     `id_partida`, 
         *                                     `id_usuario`, 
         */
        $info = require('info_juego.php');
        $this->movPermitidos = $info['mov-permitidos'];

        if(isset($_SESSION['estado_actual'])){
            $estados_futuros = $_SESSION['estados_futuros'];
            $estado_actual = $_SESSION['estado_actual'];
            $sector_vacio = $_SESSION['sector_vacio'];
            /**
             * PREVIO A CARGAR EL `id_partida` y el `id_usuario`, tengo que preguntar
             * si existen, porque se puede estar jugando una partida, sin logueo
             * lo cual esta bien, ya que esta permitido jugar sin un login
             * pero aun asi, mantengo `estados_futuros` y  `estado_actual`
             */
            if(isset($_SESSION['id_partida']) && isset($_SESSION['id_usuario'])){
                $estados_posibles = $_SESSION['id_partida'];
                $id_usuario = $_SESSION['id_usuario'];
            }
    
            /**
             * llega {`nuevo_estado_actual`, `marca_de_tiempo`}
             * 
             * busco `nuevo_estado_actual` en $estados_futuros
             * 
             * si existe => calculo: {`estados_futuros`, `sector_vacio`};
             *           => guardo: {`id_partida`, `id_usuario`, `nuevo_estado_actual`, `marca_de_tiempo`}; en: {`session`, `base de datos`}
             * si no existe => "..significa que me hizo trampa. Enconces:.."
             *              => 
             * 
             * 
             * 
             * mensaje de respuesta: 
             *          {`msj_respuesta` : {
             *                              `control_movimiento` : ['OK', 'TRAMPA'], 
             *                              `marca_de_tiempo` : 01:00:58, 
             *                              `sector_vacio` : 1,
             *                              `nuevo_estado_actual` : [..] 
             *                              `estados_futuros` : {[..], [..]}
             *                              } 
             *                          }          
             */
    
            $marca_de_tiempo = $_POST['marca_de_tiempo'];
            $nuevo_estado_actual = json_decode($_POST['nuevo_estado_actual']);

            if($this->buscarNuevoEstadoEnPosiblesFuturos($nuevo_estado_actual, $estados_futuros)){

                $msj_respuesta['marca_de_tiempo'] = $marca_de_tiempo;
                $msj_respuesta['sector_vacio'] = $_POST['sector_vacio'];
                $msj_respuesta['nuevo_estado_actual'] = $nuevo_estado_actual;
                $msj_respuesta['estados_futuros'] = $this->crear_estados_futuros($nuevo_estado_actual,$this->movPermitidos[$_POST['dificultad']]);    
                $msj_respuesta['control_movimiento'] = 'OK' ;    

                $_SESSION['estados_futuros'] = $msj_respuesta['estados_futuros'];
                $_SESSION['estado_actual'] = $msj_respuesta['nuevo_estado_actual'];
                $_SESSION['sector_vacio'] = $msj_respuesta['sector_vacio'];    

            }else{
                $msj_respuesta['control_movimiento'] = 'TRAMPA' ;    
            };    

            return json_encode($msj_respuesta);
        }else{
            $this->mostrarImagenes(); // lo redirijo a la pagina principal.
        }
    }

    public function buscarNuevoEstadoEnPosiblesFuturos($listaBuscada, $lista){
        $mov_correcto = false;
        foreach ($lista as $sublista) {
            if($listaBuscada == $sublista){
                $mov_correcto = true;
            }
        }
        return $mov_correcto;
    }

    public function login(){
        session_start();
        var_dump($_POST);
        var_dump($_SESSION);
        
        // view('login');
    }

    public function listarPartidas(){
        
    }

    public function nuevaPartida(){

    }

    public function mostrarEstadisticasUsuario(){

    }

    public function listarImagenesSubidas(){

    }

}

?>

