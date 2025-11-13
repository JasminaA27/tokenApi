<?php
// Configuración de la base de datos
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/TokenModel.php';

class ApiConsume {
    private $apiBase;
    private $tokenModel;

    public function __construct() {
        $this->apiBase = 'http://localhost/recreohuanta/index.php';
        $this->tokenModel = new TokenModel();
    }

    /**
     * Obtener un token válido de la base de datos
     */
    private function getValidToken() {
        $tokens = $this->tokenModel->getAllTokens();
        if (empty($tokens)) {
            throw new Exception('No hay tokens disponibles en la base de datos');
        }
        // Usar el primer token disponible
        return $tokens[0]['token'];
    }

    /**
     * Validar si un token específico es válido
     */
    public function validateToken($token) {
        return $this->tokenModel->tokenExists($token);
    }

    /**
     * Llamar al API de RecreoHuanta
     */
    private function callApi($endpoint, $params = []) {
        // Obtener token válido de la base de datos
        $token = $this->getValidToken();
        
        $url = $this->apiBase . '?action=api_public&method=' . $endpoint;
        
        // Agregar token a los parámetros
        $params['token'] = $token;
        
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $url .= '&' . $key . '=' . urlencode($value);
            }
        }

        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false, 
                'message' => 'Error de conexión: ' . $error,
                'http_code' => $httpCode
            ];
        }

        $data = json_decode($response, true);
        
        if (!$data) {
            return [
                'success' => false, 
                'message' => 'Respuesta inválida del API',
                'http_code' => $httpCode,
                'raw_response' => $response
            ];
        }

        return $data;
    }

    /**
     * Listar todos los recreos
     */
    public function listarRecreos() {
        return $this->callApi('listarRecreos');
    }

    /**
     * Buscar recreos por término
     */
    public function buscarRecreos($termino, $tipo = 'todo') {
        return $this->callApi('buscarRecreos', [
            'q' => $termino,
            'tipo' => $tipo
        ]);
    }

    /**
     * Obtener detalle de un recreo específico
     */
    public function verRecreo($id) {
        return $this->callApi('verRecreo', ['id' => $id]);
    }

    /**
     * Verificar estado del API de recreos
     */
    public function healthCheck() {
        $url = $this->apiBase . '?action=api_public&method=health';
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'success' => $httpCode === 200,
            'http_code' => $httpCode,
            'online' => $httpCode === 200
        ];
    }
}
?>