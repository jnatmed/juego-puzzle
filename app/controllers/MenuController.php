<?php
namespace App\controllers;
use Exception;

class MenuController {
    public static function crearMenu($tipoUsuario) {
        $NombreDelMetodo = 'crearMenu' . ucfirst($tipoUsuario);
        
        if (method_exists(self::class, $NombreDelMetodo)) {
            return self::$NombreDelMetodo();
        } else {
            throw new Exception("Tipo de usuario no válido");
        }
    }

    private static function crearMenuAnonimo() {
        return [
            ['enlace' => '/ranking', 'descripcion' => 'Ranking'],
            ['enlace' => '/login', 'descripcion' => 'Login'],
            ['enlace' => '/new', 'descripcion' => 'Nuevo Juego'],
            ['enlace' => '/traductor', 'descripcion' => 'Traductor']
        ];
    }

    private static function crearMenuJugador() {
        return [
            ['enlace' => '/ranking', 'descripcion' => 'Mi Ranking'],
            ['enlace' => '/new', 'descripcion' => 'Nuevo Juego'],
            ['enlace' => '/listado_partidas', 'descripcion' => 'Mis Partidas'],
            ['enlace' => '/datos_usuario', 'descripcion' => 'Mis datos']
        ];
    }

    private static function crearMenuAdmin() {
        return [
            ['enlace' => '/ranking', 'descripcion' => 'Ranking Jugadores'],
            ['enlace' => '/new', 'descripcion' => 'Nuevo Juego'],
            ['enlace' => '/listado_usuarios', 'descripcion' => 'Listado Usuarios'],
            ['enlace' => '/partidas', 'descripcion' => 'Partidas Activas'],
            ['enlace' => '/datos_usuario', 'descripcion' => 'Mis datos'],
            ['enlace' => '/nuevo_admin', 'descripcion' => 'Registrar Nuevo Administrador']
        ];
    }
}

