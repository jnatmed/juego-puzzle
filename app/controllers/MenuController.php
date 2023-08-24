<?php
namespace App\controllers;

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
            ['enlace' => '/new', 'descripcion' => 'Nuevo Juego']
        ];
    }

    private static function crearMenuJugador() {
        return [
            ['enlace' => '/ranking', 'descripcion' => 'Mi Ranking'],
            ['enlace' => '/login', 'descripcion' => 'Login'],
            ['enlace' => '/new', 'descripcion' => 'Nuevo Juego'],
            ['enlace' => '/listado_partidas', 'descripcion' => 'Mis Partidas']
        ];
    }

    private static function crearMenuAdmin() {
        return [
            ['enlace' => '/ranking', 'descripcion' => 'Ranking Jugadores'],
            ['enlace' => '/login', 'descripcion' => 'Login'],
            ['enlace' => '/new', 'descripcion' => 'Nuevo Juego'],
            ['enlace' => '/listado_usuarios', 'descripcion' => 'Listado Usuarios'],
            ['enlace' => '/partidas', 'descripcion' => 'Partidas Activas'],
            ['enlace' => '/nuevo_admin', 'descripcion' => 'Registrar Nuevo Administrador']
        ];
    }
}

