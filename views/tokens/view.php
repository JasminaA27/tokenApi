<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Ver Token</h1>
        <div>
            <a href="index.php?action=tokens" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
            <a href="index.php?action=tokens&method=edit&token=<?php echo urlencode($token); ?>" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información del Token</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Token API</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   id="tokenValue"
                                   value="<?php echo htmlspecialchars($token); ?>" 
                                   readonly
                                   style="background-color: #f8f9fa; font-family: 'Courier New', monospace;">
                            <button class="btn btn-outline-primary" type="button" onclick="copyToken()">
                                <i class="bi bi-clipboard me-1"></i> Copiar
                            </button>
                        </div>
                        <div class="form-text">Este es el token actual almacenado en la base de datos.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Longitud del Token</label>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            El token tiene <strong><?php echo strlen($token); ?></strong> caracteres
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo</label>
                        <div>
                            <span class="badge bg-primary">API Token</span>
                            <span class="badge bg-secondary">Autenticación</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="index.php?action=tokens&method=edit&token=<?php echo urlencode($token); ?>" 
                           class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i> Editar Token
                        </a>
                        
                        <button type="button" class="btn btn-outline-primary" onclick="copyToken()">
                            <i class="bi bi-clipboard me-2"></i> Copiar Token
                        </button>
                        
                        <a href="index.php?action=tokens" class="btn btn-outline-secondary">
                            <i class="bi bi-list me-2"></i> Ver Todos los Tokens
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Creado:</small>
                        <div class="fw-semibold">Fecha no disponible</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Última modificación:</small>
                        <div class="fw-semibold">Fecha no disponible</div>
                    </div>
                    <div>
                        <small class="text-muted">Estado:</small>
                        <span class="badge bg-success">Activo</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToken() {
    const tokenInput = document.getElementById('tokenValue');
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
document.getElementById('tokenValue').addEventListener('click', function() {
    this.select();
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>