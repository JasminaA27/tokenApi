<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function showLogin() {
        // Si ya está logueado, redirigir a tokens
        if (isLoggedIn()) {
            redirect('index.php?action=tokens');
        }

        $pageTitle = 'Iniciar Sesión - Sistema API';
        $bodyClass = 'login-page';
        include __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = clean($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                showAlert('Por favor complete todos los campos', 'error');
                $this->showLogin();
                return;
            }

            // Login simple para desarrollo
            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['admin_id'] = 1;
                $_SESSION['admin_nombre'] = 'Administrador';
                $_SESSION['admin_username'] = 'admin';
                
                showAlert('¡Bienvenido Administrador!', 'success');
                redirect('index.php?action=tokens');
            } else {
                showAlert('Usuario o contraseña incorrectos', 'error');
                $this->showLogin();
            }
        } else {
            $this->showLogin();
        }
    }

    public function logout() {
        session_destroy();
        showAlert('Sesión cerrada correctamente', 'success');
        redirect('index.php?action=login');
    }
}
?>