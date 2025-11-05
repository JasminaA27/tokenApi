<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Editar Token</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <div class="breadcrumb">
        <a href="?action=tokens">← Volver a la lista</a>
    </div>
    
    <form method="POST" class="edit-form">
        <div class="form-group">
            <label for="current_token">Token Actual:</label>
            <input type="text" id="current_token" value="<?php echo htmlspecialchars($current_token); ?>" readonly>
            <div class="form-text">Este token será reemplazado por el nuevo valor</div>
        </div>
        
        <div class="form-group">
            <label for="new_token">Nuevo Token:</label>
            <input type="text" id="new_token" name="new_token" required 
                   value="<?php echo htmlspecialchars($_POST['new_token'] ?? ''); ?>">
            <div class="form-text">Ingrese el nuevo token seguro</div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Actualizar Token
            </button>
            <a href="?action=tokens" class="btn btn-cancel">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>