<?php

namespace Lucendi;

// Uso de una biblioteca de seguridad para limpiar datos
use Symfony\Component\HttpFoundation\Request;

class Lucendi
{
    protected $apiURL;
    protected $apiKey;
    protected $apiSecret;
    protected $apiUser;

    public function __construct()
    {
        $this->apiURL = 'https://www.larapox.com/';
        $this->apiKey = getenv('LARAPOX_APP_KEY');
        $this->apiSecret = getenv('LARAPOX_APP_SECRET');
        $this->apiUser = getenv('LARAPOX_APP_USERNAME');

        // Validación simple para asegurarse de que las claves no estén vacías
        if (empty($this->apiKey) || empty($this->apiSecret) || empty($this->apiUser)) {
            throw new \Exception("Las claves de la API no están configuradas correctamente.");
        }
    }

    public function callAPI($endpoint, $params = [])
    {
        // Validar y limpiar parámetros para prevenir inyecciones
        foreach ($params as &$param) {
            $param = htmlspecialchars($param, ENT_QUOTES, 'UTF-8'); // Prevenir XSS
        }

        // Construir la URL con parámetros seguros
        $url = $this->apiURL . ltrim($endpoint, '/'); // Prevenir doble barra
        if (!empty($params)) {
            $query = http_build_query($params);
            $url .= '?' . $query;
        }

        // Asegurar que la URL utiliza HTTPS
        if (strpos($url, 'https') !== 0) {
            throw new \Exception("Conexión insegura, se requiere HTTPS.");
        }

        // Configuración de curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Tiempo de espera de 10 segundos

        // Encabezados seguros
        $headers = [
            'Authorization: Bearer ' . htmlspecialchars($this->apiKey, ENT_QUOTES, 'UTF-8'), // Proteger la clave
            'Content-Type: application/json',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Obtener la respuesta y manejar errores
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('Error en Curl: ' . curl_error($ch));
        }

        curl_close($ch);

        // Validación básica de respuesta JSON
        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error al decodificar la respuesta JSON.");
        }

        return $decoded; // Retornar datos decodificados
    }

    public function scrapeWebsite($url)
    {
        // Método vacío, agregar lógica con validaciones
    }
}
