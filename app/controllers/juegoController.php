<?php
namespace App\controllers;

use \App\models\UsuarioModel;
use \App\controllers\SessionController;

class JuegoController{

    public function new(){
        $sesion = new SessionController();
        // return $sesion->login();
        return view('nuevo_juego');
    }
}
