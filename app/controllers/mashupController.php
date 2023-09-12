<?php
namespace App\controllers;

class MashupController {
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct($datos_mashup) {
        $this->clientId = $datos_mashup['clientId'];
        $this->clientSecret = $datos_mashup['clientSecret'];
        $this->redirectUri = $datos_mashup['redirectUri'];
    }

    public function handleAuthRequest() {

        // Manejo de la solicitud de autenticación
        if (isset($_GET['code'])) {
            // Verifica el estado para evitar ataques de CSRF
            $state = $_GET['state'];
            if (!isset($_COOKIE['spotify_auth_state']) || $_COOKIE['spotify_auth_state'] !== $state) {
                die('Error de seguridad: Estado inválido.');
            }

            // Obtiene el código de autorización de Spotify
            $code = $_GET['code'];

            // Intercambia el código por un token de acceso (implementa esta función)
            $accessToken = $this->exchangeCodeForAccessToken($code);

            // Obtiene las principales canciones de Spotify (implementa esta función)
            $topTracks = $this->getTopTracks($accessToken);

            return [
                'estado' => 'ok',
                'topTracks' => $topTracks,
                'descripcion' => 'canciones principales '
            ];            
        } else {
            // Renderiza la página HTML sin las principales canciones
            return [
                'estado' => 'error',
                'topTracks' => [],
                'descripcion' => 'sin canciones principales'
            ];
        }
    }

    // Función para intercambiar el código de autorización por un token de acceso (implementa esta función)
    private function exchangeCodeForAccessToken($code) {
        $clientId = $this->clientId;
        $clientSecret = $this->clientSecret;
        $redirectUri = $this->redirectUri;
    
        // URL de Spotify para obtener el token de acceso
        $tokenUrl = 'https://accounts.spotify.com/api/token';
    
        // Datos requeridos para la solicitud
        $postData = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ];
    
        // Configura las cabeceras de autenticación
        $authHeader = base64_encode("{$clientId}:{$clientSecret}");
    
        // Configura las cabeceras de la solicitud
        $headers = [
            'Authorization: Basic ' . $authHeader,
            'Content-Type: application/x-www-form-urlencoded',
        ];
    
        // Inicia la solicitud cURL
        $ch = curl_init($tokenUrl);
        curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/../../certificado.pem');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);

        echo("<pre>");
        var_dump($verbose);
    
        // Ejecuta la solicitud
        $response = curl_exec($ch);


        // Verifica si hubo errores
        if (curl_errno($ch)) {
            die('Error al intercambiar el código por el token de acceso: ' . curl_error($ch));
        }
    
        // Cierra la conexión cURL
        curl_close($ch);
    
        // Decodifica la respuesta JSON
        $data = json_decode($response, true);
    
        // Retorna el token de acceso
        return $data['access_token'];
    }
    

    // Función para obtener las principales canciones de Spotify (implementa esta función)
    private function getTopTracks($accessToken) {
        // URL de Spotify para obtener las principales canciones del usuario
        $topTracksUrl = 'https://api.spotify.com/v1/me/top/tracks?time_range=short_term&limit=5';
    
        // Configura las cabeceras de autenticación
        $headers = [
            'Authorization: Bearer ' . $accessToken,
        ];
    
        // Inicia la solicitud cURL
        $ch = curl_init($topTracksUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        // Ejecuta la solicitud
        $response = curl_exec($ch);
    
        // Verifica si hubo errores
        if (curl_errno($ch)) {
            die('Error al obtener las principales canciones: ' . curl_error($ch));
        }
    
        // Cierra la conexión cURL
        curl_close($ch);
    
        // Decodifica la respuesta JSON
        $data = json_decode($response, true);
    
        // Retorna los datos de las principales canciones
        return $data['items'];
    }    
}
