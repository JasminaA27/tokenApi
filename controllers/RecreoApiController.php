<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/RecreoApiModel.php';

class RecreoApiController {
    private $apiModel;

    public function __construct() {
        $this->apiModel = new RecreoApiModel();
    }

    public function publicView() {
        $pageTitle = 'API Recreos Huanta - Directorio de Lugares Recreativos';
        
        // Procesar búsqueda y obtener datos
        $termino = $_GET['q'] ?? '';
        $tipo = $_GET['tipo'] ?? 'todo';
        $verId = $_GET['ver'] ?? null;

        $recreos = [];
        $recreoSeleccionado = null;
        $mensajeError = '';
        $mostrarResultados = false;
        $errorTipo = ''; // local_system, remote_system, connection
        $errorCodigo = ''; // Código específico del error

        try {
            // Verificar salud del sistema primero
            $health = $this->apiModel->healthCheck();
            
            if (!$health['local_token_valid']) {
                $mensajeError = $health['message'];
                $errorTipo = 'local_system';
                $errorCodigo = 'LOCAL_TOKEN_INVALID';
            } elseif (!$health['remote_api_accessible']) {
                $mensajeError = $health['message'];
                $errorTipo = $health['error_type'];
                $errorCodigo = $health['error_code'];
            } else {
                // Si el sistema está saludable, proceder con la búsqueda
                if (!empty($termino)) {
                    $resultado = $this->apiModel->buscarRecreos($termino, $tipo);
                } else {
                    $resultado = $this->apiModel->listarRecreos();
                }

                if ($resultado['success']) {
                    $recreos = $resultado['data'];
                    $mostrarResultados = true;
                    
                    if (!empty($recreos) && !$verId) {
                        $verId = $recreos[0]['id'];
                    }
                } else {
                    $mensajeError = $resultado['message'];
                    $errorTipo = $resultado['error_type'] ?? 'API_ERROR';
                    $errorCodigo = $resultado['error_code'] ?? 'UNKNOWN_ERROR';
                }

                if ($verId) {
                    $resultadoDetalle = $this->apiModel->verRecreo($verId);
                    if ($resultadoDetalle['success']) {
                        $recreoSeleccionado = $resultadoDetalle['data'];
                    }
                }
            }
        } catch (Exception $e) {
            $mensajeError = 'Error del sistema: ' . $e->getMessage();
            $errorTipo = 'SYSTEM_ERROR';
            $errorCodigo = 'SYSTEM_EXCEPTION';
        }

        // Pasar variables a la vista
        include __DIR__ . '/../views/recreoapi/public.php';
    }

    // Función para validar token
    public function validateToken($token) {
        return $this->apiModel->validateApiToken($token);
    }
}
?>