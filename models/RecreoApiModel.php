<?php
require_once __DIR__ . '/../config/config.php';

class RecreoApiModel {
    private $apiBase;
    private $apiToken;

    public function __construct() {
        $this->apiBase = 'http://localhost/RecreoHuanta/index.php';
        $this->apiToken = '7ed5be94b77a9d33f5d4e5d2c99703504eb108cdb6d65c87ea1962917fcb63bb_20251021_2';
    }

    private function callApi($endpoint, $params = []) {
        $url = $this->apiBase . '?action=api_public&method=' . $endpoint;
        
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
                'Authorization: Bearer ' . $this->apiToken,
                'Accept: application/json'
            ],
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
        return $this->callApi('buscarRecreos', [
            'q' => $termino,
            'tipo' => $tipo
        ]);
    }

    public function verRecreo($id) {
        return $this->callApi('verRecreo', ['id' => $id]);
    }

    // Validar token contra la base de datos de tokenApi
    public function validateApiToken($token) {
        require_once 'TokenModel.php';
        $tokenModel = new TokenModel();
        return $tokenModel->tokenExists($token);
    }
}
?>