<?php
namespace App\controllers;

use \PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class CorreoController
{
    public $remitente;
    public $pass;
    public $host;
    public $name;

    public function __construct($datos_mailer)
    {
        if (
            isset($datos_mailer['mail_from']) &&
            isset($datos_mailer['pass']) &&
            isset($datos_mailer['host']) &&
            isset($datos_mailer['name'])
        ) {
            $this->remitente = $datos_mailer['mail_from'];
            $this->pass = $datos_mailer['pass'];
            $this->host = $datos_mailer['host'];
            $this->name = $datos_mailer['name'];
        } else {
            throw new \InvalidArgumentException("Faltan datos de configuración del mailer.");
        }
    }

    public function enviarCorreo($destinatario, $asunto, $mensaje, $alias_to)
    {
            $mail = new PHPMailer(true);

            try {
                // Configurar el servidor SMTP
                $mail->isSMTP();
                $mail->Host       = $this->host;
                $mail->SMTPAuth   = true;
                // $mail->Username   = $this->remitente;
                // $mail->Password   = $this->pass;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Usar STARTTLS
                $mail->Port       = 587; // Puerto de STARTTLS
                // $mail->Port       = 25; // Puerto de STARTTLS
            
                // Configurar el correo
                $mail->CharSet    = 'UTF-8';
                $mail->setFrom($this->remitente, $this->name);
                $mail->addAddress($destinatario, $alias_to);
            
                $mail->isHTML(true);
                $mail->Subject = $asunto;
                $mail->Body    = $mensaje;
            
                // echo("<pre>");
                // var_dump($mail);
                
                $result = $mail->send();

                echo 'Correo enviado correctamente';

            } catch (Exception $e) {
                echo "Error al enviar el correo: {$mail->ErrorInfo}";
            }
    }
}
