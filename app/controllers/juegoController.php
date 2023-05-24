<?php
namespace App\controllers;

use \App\models\UsuarioModel;
use \App\controllers\SessionController;

class JuegoController{

    public function new(){
        $sesion = new SessionController();
        return view('nuevo_juego', [ 'enlaces' => [['enlace'=>'/','descripcion'=>'Ranking'],
                                             ['enlace'=>'/login','descripcion'=>'Login'],
                                             ['enlace'=>'/new','descripcion'=>'Nuevo Juego']   
                                                         ]]);
    }
}
