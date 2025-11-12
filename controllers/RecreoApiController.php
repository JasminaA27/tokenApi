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

        try {
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
            }

            if ($verId) {
                $resultadoDetalle = $this->apiModel->verRecreo($verId);
                if ($resultadoDetalle['success']) {
                    $recreoSeleccionado = $resultadoDetalle['data'];
                }
            }
        } catch (Exception $e) {
            $mensajeError = 'Error del sistema: ' . $e->getMessage();
        }

        include __DIR__ . '/../views/recreoapi/public.php';
    }

    // Función para validar token (si necesitas autenticación)
    public function validateToken($token) {
        return $this->apiModel->validateApiToken($token);
    }
}
?>