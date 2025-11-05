<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestión de Tokens API</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Tokens Existentes</h5>
        </div>
        <div class="card-body">
            <?php if (empty($tokens)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-key fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">No hay tokens registrados en la base de datos.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Token</th>
                                <th width="200">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tokens as $token): ?>
                            <tr>
                                <td>
                                    <code class="text-break"><?php echo htmlspecialchars($token['token']); ?></code>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="index.php?action=tokens&method=view&token=<?php echo urlencode($token['token']); ?>" 
                                           class="btn btn-outline-info" 
                                           title="Ver token">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <a href="index.php?action=tokens&method=edit&token=<?php echo urlencode($token['token']); ?>" 
                                           class="btn btn-outline-primary"
                                           title="Editar token">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>