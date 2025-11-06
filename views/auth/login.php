<?php 
$pageTitle = 'Iniciar Sesión - Sistema API';
$bodyClass = 'login-page';
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-card bg-white rounded shadow" style="width: 100%; max-width: 400px;">
        <!-- Header -->
        <div class="text-center p-4 bg-primary text-white rounded-top">
            <div class="bg-white-20 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                 style="width: 80px; height: 80px;">
                <i class="bi bi-shield-lock fs-2 text-white"></i>
            </div>
            <h3 class="mb-1 fw-bold">Bienvenido</h3>
            <p class="mb-0 opacity-75">Sistema Cliente Token Cliente</p>
        </div>

        <!-- Formulario -->
        <div class="p-4">
            <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=login">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" 
                           class="form-control" 
                           id="username" 
                           name="username" 
                           placeholder="Ingrese su usuario"
                           required>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Ingrese su contraseña"
                           required>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Iniciar Sesión
                    </button>
                </div>
            </form>
        </div>

        <!-- Credenciales de prueba -->
        <!-- <div class="p-3 bg-light rounded-bottom text-center">
            <small class="text-muted">
                <strong>Credenciales de prueba:</strong><br>
                Usuario: admin | Contraseña: admin123
            </small>
        </div>-->
    </div>
</div>

<style>
.login-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>