<?php
require_once 'config/config.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/TokenController.php';

// Obtener la acción de la URL
$action = $_GET['action'] ?? 'login';

try {
    switch ($action) {
        // Autenticación
        case 'login':
            $controller = new AuthController();
            $controller->login();
            break;

        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;

        // Gestión de Tokens
        case 'tokens':
            $controller = new TokenController();
            
            switch ($_GET['method'] ?? '') {
                case 'view':
                    $controller->view();
                    break;
                case 'edit':
                    $controller->edit();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;

        // Ruta por defecto
        default:
            if (isLoggedIn()) {
                redirect('index.php?action=tokens');
            } else {
                redirect('index.php?action=login');
            }
            break;
    }

} catch (Exception $e) {
    error_log("Error en router: " . $e->getMessage());
    showAlert('Ha ocurrido un error interno. Por favor intente nuevamente.', 'error');
    
    if (isLoggedIn()) {
        redirect('index.php?action=tokens');
    } else {
        redirect('index.php?action=login');
    }
}
?>