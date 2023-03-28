<?php
namespace App\controllers;

use \App\models\UsuarioModel;

class JuegoController{

    public function new(){
        return view('nuevo_juego');
    }
}
