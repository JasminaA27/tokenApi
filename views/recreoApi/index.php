<?php
// Mostrar errores
error_reporting(E_ALL); 
ini_set('display_errors', 1);

session_start();
require_once 'config.php';

$termino = $_GET['q'] ?? '';
$tipo = $_GET['tipo'] ?? 'todo';
$verId = $_GET['ver'] ?? null;

$apiConsumer = new ApiConsume();
$recreos = [];
$recreoSeleccionado = null;
$mensajeError = '';

try {
    if (!empty($termino)) {
        $resultado = $apiConsumer->buscarRecreos($termino, $tipo);
    } else {
        $resultado = $apiConsumer->listarRecreos();
    }

    if ($resultado['success']) {
        $recreos = $resultado['data'];
        
        if (!empty($recreos) && !$verId) {
            $verId = $recreos[0]['id'];
        }
    } else {
        $mensajeError = $resultado['message'];
    }

    if ($verId) {
        $resultadoDetalle = $apiConsumer->verRecreo($verId);
        if ($resultadoDetalle['success']) {
            $recreoSeleccionado = $resultadoDetalle['data'];
        }
    }
} catch (Exception $e) {
    $mensajeError = 'Error: ' . $e->getMessage();
}

function formatearPrecio($precio) {
    return (empty($precio) || $precio == '0' || $precio == 'Consultar') ? 'Consultar' : 'S/ ' . $precio;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Recreos - Sistema Token</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 text-primary">
                        <i class="bi bi-map me-2"></i>API Recreos Huanta
                    </h1>
                    <div>
                        <a href="../index.php?action=tokens" class="btn btn-outline-primary btn-sm me-2">
                            <i class="bi bi-key me-1"></i>Tokens
                        </a>
                        <a href="../index.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-house me-1"></i>Inicio
                        </a>
                    </div>
                </div>
                <p class="text-muted">Sistema integrado con validación automática de tokens</p>
            </div>
        </div>

        <!-- Búsqueda -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-2">
                    <div class="col-md-6">
                        <input type="text" name="q" class="form-control" 
                               placeholder="Buscar recreos..." value="<?= htmlspecialchars($termino) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="tipo" class="form-select">
                            <option value="todo" <?= $tipo == 'todo' ? 'selected' : '' ?>>Buscar en todo</option>
                            <option value="nombre" <?= $tipo == 'nombre' ? 'selected' : '' ?>>Solo nombres</option>
                            <option value="servicio" <?= $tipo == 'servicio' ? 'selected' : '' ?>>Solo servicios</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($mensajeError) ?></div>
        <?php endif; ?>

        <?php if (!empty($recreos)): ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Resultados (<?= count($recreos) ?>)</h6>
                        </div>
                        <div class="card-body p-0">
                            <?php foreach ($recreos as $recreo): ?>
                                <a href="?<?= http_build_query(['q' => $termino, 'tipo' => $tipo, 'ver' => $recreo['id']]) ?>" 
                                   class="list-group-item list-group-item-action <?= $recreoSeleccionado && $recreoSeleccionado['id'] == $recreo['id'] ? 'active' : '' ?>">
                                    <h6 class="mb-1"><?= htmlspecialchars($recreo['nombre']) ?></h6>
                                    <small class="d-block text-muted"><?= htmlspecialchars($recreo['ubicacion'] ?? '') ?></small>
                                    <small class="badge bg-primary"><?= formatearPrecio($recreo['precio'] ?? '') ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <?php if ($recreoSeleccionado): ?>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0"><?= htmlspecialchars($recreoSeleccionado['nombre']) ?></h4>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Ubicación:</strong> <?= htmlspecialchars($recreoSeleccionado['ubicacion'] ?? '') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Precio:</strong> <?= formatearPrecio($recreoSeleccionado['precio'] ?? '') ?>
                                    </div>
                                </div>
                                
                                <?php if (!empty($recreoSeleccionado['servicio'])): ?>
                                    <div class="mb-3">
                                        <strong>Servicios:</strong>
                                        <div class="mt-1">
                                            <?php 
                                            $servicios = is_string($recreoSeleccionado['servicio']) ? 
                                                explode(',', $recreoSeleccionado['servicio']) : 
                                                (array)$recreoSeleccionado['servicio'];
                                            foreach ($servicios as $servicio): ?>
                                                <span class="badge bg-secondary me-1"><?= htmlspecialchars(trim($servicio)) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="row">
                                    <?php if (!empty($recreoSeleccionado['telefono'])): ?>
                                    <div class="col-md-6">
                                        <strong>Teléfono:</strong> <?= htmlspecialchars($recreoSeleccionado['telefono']) ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($recreoSeleccionado['horario'])): ?>
                                    <div class="col-md-6">
                                        <strong>Horario:</strong> <?= htmlspecialchars($recreoSeleccionado['horario']) ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-body text-center text-muted">
                                <i class="bi bi-info-circle display-4"></i>
                                <p class="mt-3">Selecciona un recreo para ver los detalles</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif (!empty($termino)): ?>
            <div class="card">
                <div class="card-body text-center text-muted">
                    <i class="bi bi-search display-4"></i>
                    <p class="mt-3">No se encontraron resultados para "<?= htmlspecialchars($termino) ?>"</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>