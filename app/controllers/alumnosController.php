<?php
namespace App\controllers;

require 'vendor/autoload.php';

use \App\models\AlumnosModel;

class AlumnosController{

    public function cargarItem($item, $listado){
        foreach ($item as $key => $value){
            $item[$key] = $listado->$listado[$key];
        }        
    }

    public function traerRecibos(){
        $alumnosModel = new AlumnosModel();
        $resultado = $alumnosModel->cargarRecibos();

        foreach ($resultado as $obj) {
            $listado[] = get_object_vars($obj);
        }
        return $listado;
    }

    public function traerAlumnos(){
        $alumnosModel = new AlumnosModel();
        $resultado = $alumnosModel->cargarAlumnos();

        foreach ($resultado as $obj) {
            $listado[] = get_object_vars($obj);
        }
        return $listado;
    }

    public function listar(){
       
        return view('listado_alumnos', ['listado' => $this->traerAlumnos(), 'mensaje'=>'']);
    }

    public function verAlumno(){
        $nombre_alumno = $_GET['nombre_alumno'];
        $alumnoModel = new AlumnosModel();
        $resultado = $alumnoModel->verAlumno($nombre_alumno);
     
       return view('ver_alumno',['datos_alumno'=>get_object_vars($resultado[0])]);
    }

    public function buscarAlumno(){
        $nombre_alumno = $_POST['nombre_alumno'];
        $alumnoModel = new AlumnosModel();
        $resultado = $alumnoModel->verAlumno($nombre_alumno);
        $resultado = get_object_vars($resultado[0]);

        echo(json_encode(array('grado' => $resultado['grado'])));
    
    }

    public function buscarContrasenia(){
        $nombre_usuario = $_POST['nombre_usuario'];
        $alumnoModel = new AlumnosModel();
        $resultado = $alumnoModel->verAlumno($nombre_usuario);
        $resultado = get_object_vars($resultado[0]);

        echo(json_encode(array('respuesta' => $resultado['pass'])));

    }

    public function verRecibos(){
        
        return view('listado_recibos', ['listado' => $this->traerRecibos()]);
    }

    public function traerPadres(){
        $alumnosModel = new AlumnosModel();
        $resultado = $alumnosModel->cargarPadres();

        foreach ($resultado as $obj) {
            $listado[] = get_object_vars($obj);
        }
        return $listado;
    }

    public function nuevoAlumno(){
        $listado = $this->traerPadres();
        return view('nuevo_alumno', ['listado' => $listado]);
    }
    
    public function nuevoPadre(){
        $listado = $this->traerAlumnos();
        return view('nuevo_padre',['listado' => $listado]);
    }
    public function nuevoRecibo(){
        return view('nuevo_recibo', ['listado' => $this->traerAlumnos()]);
    }

    public function listarPadres(){
        $listado = $this->traerPadres();       
        return view('listado_padres', ['listado' => $listado]);
    }
    
    public function guardarAlumno(){
        $alumnosModel = new AlumnosModel();
        $_POST['nombre_padre'] = $_POST['nombre_padre'] == '' ? 'NULL' : $_POST['nombre_padre'];
        $resultado = $alumnosModel->guardarAlumno($_POST);
        if ($resultado<>23000){               
                return view('listado_alumnos', ['listado' => $this->traerAlumnos(), 'mensaje'=>'alumno agregado']);
           }else{           
            if($resultado==23000){
                return view('excepciones',["mensaje"=>'EL ALUMNO YA EXISTE - (Error: 23000)']);
            }else{
                return view('excepciones',["mensaje"=>$resultado]);
            };
        };
    }

    public function guardarPadre(){
        $alumnosModel = new AlumnosModel();
        $_POST['nombre_alumno'] = $_POST['nombre_alumno'] == '' ? 'NULL' : $_POST['nombre_alumno'];
        $resultado = $alumnosModel->guardarPadre($_POST);
        if ($resultado<>23000){               
                return view('listado_padres', ['listado' => $this->traerPadres(), 'mensaje'=>'padre agregado']);
           }else{           
            if($resultado==23000){
                return view('excepciones',["mensaje"=>'EL PADRE YA EXISTE - (Error: 23000)']);
            }else{
                return view('excepciones',["mensaje"=>$resultado]);
            };
        };
    }

    public function guardarRecibo(){
        // echo("<pre>");
        // var_dump($_POST);
        // var_dump($_FILES);
        if (isset($_POST['enviar']) && $_POST['enviar'] == 'enviar') {
            if (isset($_FILES['archivo_recibo']) && $_FILES['archivo_recibo']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['archivo_recibo']['tmp_name'];
                $fileName = $_FILES['archivo_recibo']['name'];
                $fileSize = $_FILES['archivo_recibo']['size'];
                $fileType = $_FILES['archivo_recibo']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
	
                $newFileName = $fileName;

                // extensiones de archivo permitidas
                $allowedfileExtensions = array('pdf', 'jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    // directorio en el cual sera subido el archivo
                    $uploadFileDir = 'recibos/';
                    $dest_path = $uploadFileDir . $newFileName;
                    
                    $alumnosModel = new AlumnosModel();
                    $alumnosModel->guardarRecibo(
                        [
                        'nombre_alumno' => $_POST['nombre_alumno'],
                        'grado' => $_POST['grado'],
                        'mes_cuota' => $_POST['mes_cuota'],
                        'nro_recibo ' => $_POST['nro_recibo'],
                        'ruta_archivo' => $dest_path
                        ]
                    );
                    // echo($dest_path);

                    if(move_uploaded_file($fileTmpPath, $dest_path)){
                        return view('listado_recibos', ['listado' => $this->traerRecibos()]);
                    }
                    else{
                        $mensaje = 'Hubo un error al mover el archivo al directorio. Por favor asegurese de que el directorio sea accesible por el servidor.';
                        return view('excepciones',["mensaje"=>$mensaje]);                        
                    }
                }
            }
        }
    }
}