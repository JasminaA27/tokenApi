<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/TokenModel.php';

class TokenController {
    private $tokenModel;

    public function __construct() {
        requireLogin();
        $this->tokenModel = new TokenModel();
    }

    public function index() {
        $tokens = $this->tokenModel->getAllTokens();
        $pageTitle = 'Gestión de Tokens';
        $action = 'tokens';
        include __DIR__ . '/../views/tokens/index.php';
    }

    public function view() {
        $token = $_GET['token'] ?? '';
        
        if (!$token) {
            showAlert('Token no especificado', 'error');
            redirect('index.php?action=tokens');
        }

        if (!$this->tokenModel->tokenExists($token)) {
            showAlert('Token no encontrado', 'error');
            redirect('index.php?action=tokens');
        }

        $pageTitle = 'Ver Token';
        $action = 'tokens';
        include __DIR__ . '/../views/tokens/view.php';
    }

    public function edit() {
        $current_token = $_GET['token'] ?? '';
        
        if (!$current_token) {
            showAlert('Token no especificado', 'error');
            redirect('index.php?action=tokens');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_token = clean($_POST['new_token'] ?? '');
            
            if (empty($new_token)) {
                showAlert('El token no puede estar vacío', 'error');
            } elseif ($this->tokenModel->updateToken($current_token, $new_token)) {
                showAlert('Token actualizado correctamente', 'success');
                redirect('index.php?action=tokens');
            } else {
                showAlert('Error al actualizar el token', 'error');
            }
        }

        $pageTitle = 'Editar Token';
        $action = 'tokens';
        include __DIR__ . '/../views/tokens/edit.php';
    }
}
?>