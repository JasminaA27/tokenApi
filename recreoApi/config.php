<?php
// Configuración de la base de datos
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/TokenModel.php';

class ApiConsume {
    private $apiBase;
    private $tokenModel;

    public function __construct() {
        $this->apiBase = 'http://localhost:8888/RecreoHuanta/index.php';
        $this->tokenModel = new TokenModel();
    }

    private function getValidToken() {
        $tokens = $this->tokenModel->getAllTokens();
        if (empty($tokens)) {
            throw new Exception('No hay tokens disponibles');
        }
        return $tokens[0]['token'];
    }

    private function callApi($endpoint, $params = []) {
        $token = $this->getValidToken();
        $url = $this->apiBase . '?action=api_public&method=' . $endpoint;
        
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
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'message' => 'Error de conexión: ' . $error];
        }

        $data = json_decode($response, true);
        return $data ?: ['success' => false, 'message' => 'Respuesta inválida'];
    }

    public function listarRecreos() {
        return $this->callApi('listarRecreos');
    }

    public function buscarRecreos($termino, $tipo = 'todo') {
        return $this->callApi('buscarRecreos', ['q' => $termino, 'tipo' => $tipo]);
    }

    public function verRecreo($id) {
        return $this->callApi('verRecreo', ['id' => $id]);
    }
}
?>