<?php
namespace App\controllers;

require 'vendor/autoload.php';

use \Monolog\Logger;
use \Monolog\Handler\RotatingFileHandler;
use \Monolog\Handler\BrowserConsoleHandler;

use \App\models\AlumnosModel;

class AlumnosController{

    public function listar(){
        $alumnosModel = new AlumnosModel();
        $resultado = $alumnosModel->cargarAlumnos();

        // echo("<pre>");
        // var_dump($resultado);}
        foreach ($resultado as $key => $value) {
            // echo("<pre>");
            // var_dump($value->nombre_alumno);
            $item['nombre_alumno'] = $value->nombre_alumno;
            $item['fecha_nacimiento'] = $value->fecha_nacimiento;
            $item['grado'] = $value->grado;
            $item['nombre_padre'] = $value->nombre_padre;
            $listado[] = $item;
        }
        // echo("<pre>");
        // var_dump($listado);
        
        return view('listado_alumnos', ['listado' => $listado]);
    }

    public function verAlumno(){
        $nombre_alumno = $_GET['nombre_alumno'];
        $alumnoModel = new AlumnosModel();
        $resultado = $alumnoModel->verAlumno($nombre_alumno);
        $value = $resultado[0];

        $item['nombre_alumno'] = $value->nombre_alumno;
        $item['fecha_nacimiento'] = $value->fecha_nacimiento;
        $item['grado'] = $value->grado;
        $item['nombre_padre'] = $value->nombre_padre;
     
       return view('ver_alumno',['datos_alumno'=>$item]);
    }

}