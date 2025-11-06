<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Editar Token</h1>
        <a href="?action=tokens" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a la lista
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actualizar Token</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-4">
                            <label for="current_token" class="form-label fw-bold">Token Actual</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="current_token" 
                                       value="<?php echo htmlspecialchars($current_token); ?>" 
                                       readonly
                                       style="background-color: #f8f9fa; font-family: 'Courier New', monospace;">
                                <button class="btn btn-outline-secondary" type="button" onclick="copyCurrentToken()">
                                    <i class="bi bi-clipboard me-1"></i> Copiar
                                </button>
                            </div>
                            <div class="form-text">Este token será reemplazado por el nuevo valor.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="new_token" class="form-label fw-bold">Nuevo Token</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="new_token" 
                                   name="new_token" 
                                   required
                                   value="<?php echo htmlspecialchars($_POST['new_token'] ?? ''); ?>"
                                   placeholder="Ingrese el nuevo token seguro"
                                   style="font-family: 'Courier New', monospace;">
                            <div class="form-text">Ingrese el nuevo token seguro para autenticación.</div>
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="?action=tokens" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Actualizar Token
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCurrentToken() {
    const tokenInput = document.getElementById('current_token');
    tokenInput.select();
    tokenInput.setSelectionRange(0, 99999); // Para móviles
    
    navigator.clipboard.writeText(tokenInput.value).then(() => {
        // Mostrar notificación de éxito
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alert.innerHTML = `
            <i class="bi bi-check-circle me-2"></i>
            Token copiado al portapapeles
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        // Auto-remover después de 3 segundos
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 3000);
    }).catch(err => {
        console.error('Error al copiar: ', err);
        alert('Error al copiar el token');
    });
}

// Seleccionar automáticamente el token al hacer clic en el input
document.getElementById('current_token').addEventListener('click', function() {
    this.select();
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>