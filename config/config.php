<?php
// Configuración de la base de datos
const BD_HOST = 'localhost';
const BD_NAME = 'cliente_api';
const BD_USER = 'root';
const BD_PASSWORD = 'root';
const BD_CHARSET = 'utf8';

// URLs del proyecto
const BASE_URL = 'http://localhost:8888/tokenApi/';

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0);

// Zona horaria
date_default_timezone_set('America/Lima');

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Función para redirigir
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

// Función para verificar si está logueado
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Función para requerir login
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('index.php?action=login');
    }
}

// Función para mostrar alertas
function showAlert($message, $type = 'success') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

// Función para obtener y limpiar alertas
function getAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}

// Función para limpiar datos de entrada
function clean($data) {
    return htmlspecialchars(trim($data));
}
?>