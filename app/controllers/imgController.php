<?php 
namespace App\controllers;

use GD;

class ImgController{
    public $ancho;
    public $alto;
    public $tamanio_pieza;

    public function __construct($dimensiones){
        $this->ancho = $dimensiones['ANCHO'];
        $this->alto = $dimensiones['ALTO'];
        $this->tamanio_pieza = $dimensiones['TAMANIO_PIEZA'];
    }

    /**
     * obtengo la imagen, y la redimensiono de acuerdo a la informacion
     * proporcionada en el archivo info.php
     * de manera de tener el manejo de los parametros en un archivo 
     * aparte.
     */
    public function redimensionar($img){
        $infoImg = getimagesize($img);
        $ancho_img = $infoImg[0];
        $alto_img = $infoImg[1];

        list($a, $solo_tipo) = explode("/",$infoImg['mime']);
        /**
         * aca obtengo el tipo [jpeg, jpg, png]
         * pero tengo que hacerle un explode al campo $infoImg['mime']
         * imagecopyresampled($dst_img,
         *                    $original_image_data, 0, 0, 0, 0, 
         *                    $new_width,
         *                    $new_height,
         *                    $original_width, 
         *                    $original_height)
         */
        $img_redimencionada = ImageCreateTrueColor($this->getAnchoImg(), $this->getAltoImg());

        imagecopyresampled($img_redimencionada, // imagen destino
                           imagecreatefromjpeg($img), // imagen original 
                           
                           0, // coord x inicio imagen destino 
                           0, // coord y inicio imagen destino
                           0, // coord x inicio imagen original
                           0, // coord x inicio imagen original
                           $this->getAnchoImg(), // ancho imagen destino
                           $this->getAltoImg(),  // alto imagen destino
                           $ancho_img, // ancho imagen original
                           $alto_img ); // alto imagen original
        $img_format_jpeg = $img_redimencionada;

        return array('formato_jpeg' => $img_format_jpeg);
    }
    
    
    public function getAltoImg(){
        return $this->alto;
    }
    public function getAnchoImg(){
        return $this->ancho;
    }
    
    public function getTamanioPieza(){
        return $this->tamanio_pieza;
    }

    public function tipoImg($img){
        $infoImg = getimagesize($img);
    }
    
    public function recortar($img, $coorx, $coory,$imgJPEG=NULL){
            
        $imgRecortada = imagecrop($imgJPEG, 
                                  ['x' => $coorx, 
                                   'y' => $coory, 
                                   'width' => $this->getTamanioPieza(), 
                                   'height' => $this->getTamanioPieza()]);
        ob_start();
        imagejpeg($imgRecortada, NULL, 100);
        imageDestroy($imgRecortada);
        $img_resultado = ob_get_clean();
        $img_resultado = base64_encode($img_resultado);
       
        return array('original' => $img, 'resultado' => $img_resultado);
    }   
    
}

?>