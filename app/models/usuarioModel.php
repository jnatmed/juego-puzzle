<?php
namespace App\models;

use App\Core\Model;
use PDO;
use PDOException;

class UsuarioModel extends Model{
    public $db;
    const TOKEN_VALIDADO = 'validada' ;
    // private $id_usuario;
    // private $tipo_usuario;

    public function existeUsuario($id_usuario){

        $resultadoConsulta = $this->db->selectOne('usuario','id_usuario',$id_usuario);        

        if($resultadoConsulta['estado'] == 'error') {
            return false;
        }else{
            return true;
        }  
    }   

    public function iniciar($datosUsuario){

        try {

            $resultadoConsulta = $this->db->buscarUsuario($datosUsuario);
           
            if(!empty($resultadoConsulta)){

                    $_SESSION = [
                        'id_usuario' => $resultadoConsulta['id_usuario'],
                        'alias' => $resultadoConsulta['alias'],
                        'tipo_usuario' => $resultadoConsulta['tipo_usuario']
                    ];

                    return [
                        'estado' => 'ok',
                        'contrasenia_correcta' => true,
                        'id_usuario' => $resultadoConsulta['id_usuario']];
            }else{
            return ['estado' => 'error',
                    'codigo' => 1,
                    'descripcion' => 'error al iniciar sesion'];
            }
        } catch (PDOException $e) {
            echo("Error al Iniciar Session: ".$e);
        }
    }

    public function validarToken($token_sin_validar, $id_usuario){

        $resultado = $this->db->selectOne('usuario', 'id_usuario', $id_usuario);

        if($resultado['estado'] == 'ok'){
            /**comparar token_sin_validar */
            if ($resultado['token_validacion'] == $token_sin_validar){

                /**actualizar la estado_validacion 
                 * de la tabla de usuario */
                $parameters = [
                    'estado_validacion' => self::TOKEN_VALIDADO
                ];
                $condition = "id_usuario = '{$id_usuario}'";

                $resultado_update = $this->db->update('usuario', $parameters, $condition);

                if($resultado_update['estado'] == 'ok'){
                    return [
                        'estado' => 'ok',
                        'description' => $resultado_update['descripcion']
                    ];
                }else{
                    return [
                        'estado' => 'error',
                        'description' => $resultado_update['descripcion']
                    ];
                }
            }else{
                return [
                    'estado' => 'error',
                    'descripcion' => 'los token no coinciden'
                ];
            }
        }
        // $resultado = $this->db->
    }

    public function registrarNuevo($datos_registro){
        
        if(!$this->existeUsuario($datos_registro['id_usuario'])){
            $_SESSION = [
                'id_usuario' => $datos_registro['id_usuario'],
                'alias' => $datos_registro['alias'],
                'tipo_usuario' => $datos_registro['tipo_usuario']
            ];
            return $this->db->insert("usuario", $datos_registro);
        }else {
            return ['estado' => 'error',
                    'codigo' => 1,
                    'descripcion' => 'el usuario ya existe en la base'];
        }
    }

    public function listadoUsuarios(){
        return $this->db->selectAll('usuario');
    }

    public function traerDatosUsuario($id_usuario){
        $datos = $this->db->selectOne('usuario', 'id_usuario', $id_usuario);        

        return $datos;

    }
}


?>