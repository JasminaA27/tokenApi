<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/TokenModel.php';

class RecreoApiModel {
    private $apiBase;
    private $tokenModel;

    public function __construct() {
        $this->apiBase = 'http://localhost/RecreoHuanta/index.php';
        $this->tokenModel = new TokenModel();
    }

    /**
     * Obtener token válido de la base de datos local (SIEMPRE fresco)
     */
    private function getValidLocalToken() {
        $tokens = $this->tokenModel->getAllTokens();
        
        if (empty($tokens)) {
            throw new Exception('No hay tokens configurados en este sistema.');
        }
        
        $token = $tokens[0]['token'];
        
        // Validar que el token no esté vacío
        if (empty($token)) {
            throw new Exception('Token local está vacío.');
        }
        
        return $token;
    }

    /**
     * Validar si el token local está activo
     */
    public function validateLocalToken($token = null) {
        if ($token === null) {
            $token = $this->getValidLocalToken();
        }
        
        return $this->tokenModel->tokenExists($token);
    }

    private function callApi($endpoint, $params = []) {
        // OBTENER TOKEN FRESCO DE LA BD EN CADA LLAMADA
        $currentToken = $this->getValidLocalToken();
        
        // PRIMERA VALIDACIÓN: Token en BD local
        if (!$this->validateLocalToken($currentToken)) {
            return [
                'success' => false, 
                'message' => 'Token local no coincide con la base de datos.',
                'error_code' => 'LOCAL_TOKEN_INVALID',
                'error_type' => 'local_system'
            ];
        }

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
                'Authorization: Bearer ' . $currentToken,
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
                'message' => 'Error de conexión con el sistema principal.',
                'error_code' => 'CONNECTION_ERROR',
                'error_type' => 'connection'
            ];
        }

        $data = json_decode($response, true);
        
        // SEGUNDA VALIDACIÓN: Respuesta del Sistema General
        if (!$data) {
            return [
                'success' => false, 
                'message' => 'Respuesta inválida del sistema principal.',
                'error_code' => 'INVALID_RESPONSE',
                'error_type' => 'connection'
            ];
        }
        
        // Si el Sistema General devuelve error de token
        if (isset($data['success']) && !$data['success']) {
            $errorMessage = $data['message'] ?? '';
            
            // Detectar tipo específico de error del sistema principal
            if (strpos($errorMessage, 'inactivo') !== false || strpos($errorMessage, 'inactive') !== false) {
                return [
                    'success' => false,
                    'message' => 'Token inactivo.',
                    'error_code' => 'REMOTE_TOKEN_INACTIVE',
                    'error_type' => 'remote_system',
                    'remote_message' => $errorMessage
                ];
            } elseif (strpos($errorMessage, 'no existe') !== false || strpos($errorMessage, 'not found') !== false) {
                return [
                    'success' => false,
                    'message' => 'Token invalido no existe en el sistema principal.',
                    'error_code' => 'REMOTE_TOKEN_NOT_FOUND',
                    'error_type' => 'remote_system',
                    'remote_message' => $errorMessage
                ];
            } elseif (strpos($errorMessage, 'token') !== false || 
                     strpos($errorMessage, 'Token') !== false ||
                     strpos($errorMessage, 'autenticación') !== false ||
                     strpos($errorMessage, 'autorización') !== false) {
                return [
                    'success' => false,
                    'message' => 'Token inválido en el sistema principal.',
                    'error_code' => 'REMOTE_TOKEN_INVALID',
                    'error_type' => 'remote_system',
                    'remote_message' => $errorMessage
                ];
            }
        }
        
        return $data;
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

    /**
     * Verificar estado del token y conexión
     */
    public function healthCheck() {
        // Obtener token fresco
        try {
            $currentToken = $this->getValidLocalToken();
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'local_token_valid' => false,
                'remote_api_accessible' => false,
                'error_type' => 'local_system'
            ];
        }

        // Verificar token local
        $localTokenValid = $this->validateLocalToken($currentToken);
        
        if (!$localTokenValid) {
            return [
                'success' => false,
                'message' => 'Token local no coincide con la base de datos.',
                'local_token_valid' => false,
                'remote_api_accessible' => false,
                'error_type' => 'local_system'
            ];
        }

        // Verificar conexión con API remota
        $testCall = $this->listarRecreos();
        
        $healthData = [
            'success' => $testCall['success'] ?? false,
            'message' => $testCall['message'] ?? 'Sistema funcionando correctamente',
            'local_token_valid' => true,
            'remote_api_accessible' => $testCall['success'] ?? false,
            'remote_message' => $testCall['message'] ?? '',
            'error_type' => $testCall['error_type'] ?? 'none',
            'error_code' => $testCall['error_code'] ?? 'none'
        ];

        return $healthData;
    }

    // Mantener compatibilidad con método existente
    public function validateApiToken($token) {
        return $this->validateLocalToken($token);
    }
}
?>