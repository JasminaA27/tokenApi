<?php 
$pageTitle = 'Iniciar Sesión - Sistema Recreos';
$bodyClass = 'login-page';
$action = 'login';
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-card" style="width: 100%; max-width: 450px; background: white; border-radius: var(--border-radius-lg); box-shadow: var(--shadow-xl); max-width: 400px;overflow: hidden;">
        <!-- Header del login -->
        <div class="text-center p-4" style="background: var(--gradient-primary); color: RoyalBlue;">
            <div class="stats-icon" style="width: 80px; height: 80px; font-size: 2rem; background: rgba(220, 220, 220); color: MediumBlue; margin: 0 auto 1rem;">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h3 class="mb-1 fw-bold">Bienvenido</h3>
            <p class="mb-0 opacity-75">Panel Administrativo</p>
        </div>

        <!-- Cuerpo del login -->
        <div class="p-5">
            <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=login" id="loginForm">
                <div class="mb-4">
                    <label for="username" class="form-label">
                        <i class="bi bi-person-circle text-primary"></i>
                        Usuario
                    </label>
                    <div class="position-relative">
                        <input type="text" 
                               class="form-control form-control-lg ps-5" 
                               id="username" 
                               name="username" 
                               placeholder="Ingrese su usuario"
                               value="<?php echo $_POST['username'] ?? ''; ?>"
                               required
                               style="background: rgba(102, 126, 234, 0.05); border: 2px solid rgba(102, 126, 234, 0.1);">
                        <i class="bi bi-person position-absolute top-50 start-0 translate-middle-y ms-3 text-primary"></i>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock-fill text-primary"></i>
                        Contraseña
                    </label>
                    <div class="position-relative">
                        <input type="password" 
                               class="form-control form-control-lg ps-5 pe-5" 
                               id="password" 
                               name="password" 
                               placeholder="Ingrese su contraseña"
                               required
                               style="background: rgba(102, 126, 234, 0.05); border: 2px solid rgba(102, 126, 234, 0.1);">
                        <i class="bi bi-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-primary"></i>
                        <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0" 
                                type="button" 
                                id="togglePassword"
                                style="background: none; border: none; color: var(--gray-400);">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold" style="padding: 1rem; letter-spacing: 0.5px;">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Iniciar Sesión
                    </button>
                </div>

                <div class="text-center">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Acceso exclusivo para administradores
                    </small>
                </div>
            </form>
        </div>

        <!-- Footer del login -->
        <div class="text-center p-3" style="background: var(--gray-50); border-top: 1px solid var(--gray-200);">
            <small class="text-muted d-flex align-items-center justify-content-center">
                <i class="bi bi-hexagon-fill text-primary me-1"></i>
                Sistema de Gestión de Recreos
            </small>
        </div>
    </div>
</div>

<!-- Partículas de fondo (efecto decorativo) -->
<div class="position-fixed top-0 start-0 w-100 h-100" style="z-index: -1; overflow: hidden;">
    <div class="position-absolute" style="top: 10%; left: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 6s ease-in-out infinite;"></div>
    <div class="position-absolute" style="top: 70%; left: 80%; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; animation: float 8s ease-in-out infinite reverse;"></div>
    <div class="position-absolute" style="top: 30%; left: 70%; width: 80px; height: 80px; background: rgba(255,255,255,0.08); border-radius: 50%; animation: float 7s ease-in-out infinite;"></div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.login-card {
    animation: slideInUp 0.8s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Toggle password visibility con animación
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.className = 'bi bi-eye-slash';
        this.style.color = 'var(--primary-color)';
    } else {
        password.type = 'password';
        icon.className = 'bi bi-eye';
        this.style.color = 'var(--gray-400)';
    }
});

// Validación del formulario con efectos
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    
    if (username === '' || password === '') {
        e.preventDefault();
        
        // Efecto de shake para campos vacíos
        if (username === '') {
            document.getElementById('username').style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                document.getElementById('username').style.animation = '';
            }, 500);
        }
        
        if (password === '') {
            document.getElementById('password').style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                document.getElementById('password').style.animation = '';
            }, 500);
        }
        
        // Mostrar mensaje
        if (!document.querySelector('.alert-danger')) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show animate-slide-in';
            alert.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-3"></i>
                    <div>Por favor complete todos los campos</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            this.insertBefore(alert, this.firstChild);
        }
    }
});

// Focus automático con efecto
document.addEventListener('DOMContentLoaded', function() {
    const usernameInput = document.getElementById('username');
    setTimeout(() => {
        usernameInput.focus();
        usernameInput.style.transform = 'scale(1.02)';
        setTimeout(() => {
            usernameInput.style.transform = 'scale(1)';
        }, 200);
    }, 500);
});

// Efectos de los campos
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('focus', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 8px 25px rgba(102, 126, 234, 0.15)';
    });
    
    input.addEventListener('blur', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
    });
});
</script>

<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>