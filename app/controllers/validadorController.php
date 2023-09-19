<?php
namespace App\controllers;

class ValidadorController
{
    public $correoController;
    public $datosCorreoFile; 
    public $mi_sitio; 

    public function __construct($correoController, $mailer)
    {
        $this->datosCorreoFile = $mailer['mail_from'];
        $this->mi_sitio = $mailer['mi_sitio'];
        $this->correoController = $correoController;
    }

    // Función para generar un token/hash único
    public function generarToken($correo, $id_usuario)
    {
        $hash = hash('sha256', $correo.$id_usuario ); 

        return $hash; // Genera un hash en base al correo y el id_usuario
    }

    // Función para enviar un correo de validación
    public function enviarCorreoValidacion($destinatario, $token, $alias_to, $id)
    {
        // Configurar y enviar el correo de validación
        $asunto = 'Confirmación de correo electrónico';
        $mensaje = "Por favor, haga clic en el siguiente enlace para confirmar su correo electrónico:\n\n";
        $mensaje .= "{$this->mi_sitio}/confirmar_correo?id={$id}&token={$token}";

        // Asume que $correoController tiene un método para enviar correos
        $this->correoController->enviarCorreo($destinatario, $asunto, $mensaje, $alias_to);

        return true; // El correo se envió con éxito
    }

    // Función para validar el token de confirmación
    public function validar($token, $id_usuario)
    {
        // Leer los tokens almacenados en el archivo JSON
        $datosCorreo = json_decode(file_get_contents($this->datosCorreoFile), true);

        // Verificar si el token recibido coincide con el almacenado
        if (isset($datosCorreo[$id_usuario]) && $datosCorreo[$id_usuario] === $token) {
            // Eliminar el token después de la validación
            unset($datosCorreo[$id_usuario]);
            file_put_contents($this->datosCorreoFile, json_encode($datosCorreo));
            return true; // El token es válido
        }

        return false; // El token no es válido
    }
    public function generarFechaExpiracion()
    {
        // Obtener la fecha y hora actual
        $fecha_actual = date('Y-m-d H:i:s');

        // Definir un período de tiempo (por ejemplo, 24 horas)
        $periodo_expiracion = new \DateInterval('P1D'); // P1D representa 1 día

        // Calcular la fecha de expiración sumando el período de tiempo
        $fecha_expiracion = new \DateTime($fecha_actual);
        $fecha_expiracion->add($periodo_expiracion);

        // Formatear la fecha de expiración como una cadena (puedes ajustar el formato según tus necesidades)
        $fecha_expiracion_enlace = $fecha_expiracion->format('Y-m-d H:i:s');

        return $fecha_expiracion_enlace;
    }

    public static function validarContrasena($contrasena) {
        $resultado = [
            'estado' => 'validado',
            'descripcion' => 'contraseña validada',
        ];
    
        // Verificar que la contraseña tenga al menos 8 caracteres
        if (strlen($contrasena) < 8) {
            $resultado['estado'] = 'error';
            $resultado['descripcion'] = 'La contraseña debe tener al menos 8 caracteres';
        }
        
        // Verificar que la contraseña contenga al menos una letra minúscula
        if (!preg_match('/[a-z]/', $contrasena)) {
            $resultado['estado'] = 'error';
            $resultado['descripcion'] = 'La contraseña debe contener al menos una letra minúscula';
        }
        
        // Verificar que la contraseña contenga al menos una letra mayúscula
        if (!preg_match('/[A-Z]/', $contrasena)) {
            $resultado['estado'] = 'error';
            $resultado['descripcion'] = 'La contraseña debe contener al menos una letra mayúscula';
        }
        
        // Verificar que la contraseña contenga al menos un dígito
        if (!preg_match('/\d/', $contrasena)) {
            $resultado['estado'] = 'error';
            $resultado['descripcion'] = 'La contraseña debe contener al menos un dígito';
        }
        
        // Verificar que la contraseña contenga al menos un carácter especial
        if (!preg_match('/[@$!%*?&]/', $contrasena)) {
            $resultado['estado'] = 'error';
            $resultado['descripcion'] = 'La contraseña debe contener al menos un carácter especial (@ $ ! % * ? &)';
        }
        
        return $resultado;
    }

}
