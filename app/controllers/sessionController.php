<?php
namespace App\controllers;

class SessionController{
    public $session;
    public function __construct($session){
        session_start();
        $_SESSION['id'] = rand();
    }   
}

?>