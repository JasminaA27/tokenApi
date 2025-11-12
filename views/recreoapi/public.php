<?php
// Funciones helper
function formatearPrecio($precio) {
    if (empty($precio) || $precio == '0' || $precio == 'Consultar') {
        return 'Consultar';
    }
    return 'S/ ' . $precio;
}

function obtenerServicios($recreo) {
    $servicios = [];
    
    if (!empty($recreo['servicio'])) {
        if (is_string($recreo['servicio'])) {
            $servicios = array_map('trim', explode(',', $recreo['servicio']));
        } else {
            $servicios = (array)$recreo['servicio'];
        }
    }
    
    return array_filter($servicios);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #047857;
            --secondary-color: #059669;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .header-custom {
            background-color: var(--primary-color);
            color: white;
            padding: 2.5rem 0;
        }
        
        .result-item {
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        
        .result-item:hover {
            border-color: var(--primary-color) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .result-item.active {
            border-color: var(--primary-color) !important;
            background-color: #f0fdf4;
        }
        
        .price-badge {
            background-color: var(--primary-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .filter-btn.active {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .info-card {
            background-color: #f8f9fa;
        }
        
        .results-sidebar {
            max-height: 750px;
            overflow-y: auto;
        }
        
        .footer-custom {
            background-color: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-custom">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">API Recreos Huanta</h1>
            <p class="lead mb-0">Directorio oficial de lugares recreativos en Huanta</p>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container my-4">
        <!-- Search Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h2 class="h4 mb-4">Encuentra tu lugar ideal</h2>
                
                <form method="GET" action="" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" name="q" class="form-control form-control-lg" 
                                   placeholder="Buscar por nombre, servicio o ubicación..." 
                                   value="<?php echo htmlspecialchars($termino); ?>">
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-search me-2"></i>Buscar Recreos
                            </button>
                        </div>
                        <?php if (!empty($termino) || !empty($verId)): ?>
                        <div class="col-md-auto">
                            <a href="?" class="btn btn-outline-secondary btn-lg w-100">
                                <i class="fas fa-times me-2"></i>Limpiar
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </form>
                
                <!-- Filtros -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="?q=<?php echo urlencode($termino); ?>&tipo=todo" 
                       class="btn btn-outline-primary filter-btn <?php echo $tipo == 'todo' ? 'active' : ''; ?>">
                        <i class="fas fa-search me-1"></i> Búsqueda General
                    </a>
                    <a href="?q=<?php echo urlencode($termino); ?>&tipo=nombre" 
                       class="btn btn-outline-primary filter-btn <?php echo $tipo == 'nombre' ? 'active' : ''; ?>">
                        <i class="fas fa-signature me-1"></i> Por Nombre
                    </a>
                    <a href="?q=<?php echo urlencode($termino); ?>&tipo=servicio" 
                       class="btn btn-outline-primary filter-btn <?php echo $tipo == 'servicio' ? 'active' : ''; ?>">
                        <i class="fas fa-concierge-bell me-1"></i> Por Servicio
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="border-top pt-3 text-muted">
                    <?php if ($mostrarResultados && !empty($termino)): ?>
                        <i class="fas fa-chart-bar me-1"></i>
                        Se encontraron <?php echo count($recreos); ?> recreos
                        para "<?php echo htmlspecialchars($termino); ?>"
                    <?php else: ?>
                        <i class="fas fa-compass me-1"></i>
                        Explora los mejores lugares recreativos de Huanta
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error al cargar los datos</strong>
                <p class="mb-0"><?php echo htmlspecialchars($mensajeError); ?></p>
            </div>
        <?php endif; ?>

        <!-- Results Section -->
        <?php if ($mostrarResultados): ?>
            <div class="row g-4">
                <!-- Results Sidebar -->
                <div class="col-lg-4">
                    <div class="card shadow-sm results-sidebar">
                        <div class="card-body p-3">
                            <?php if (empty($recreos)): ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-search fa-3x mb-3"></i>
                                    <h5>No se encontraron resultados</h5>
                                    <p>Intenta con otros términos de búsqueda</p>
                                </div>
                            <?php else: ?>
                                <div class="d-flex flex-column gap-2">
                                    <?php foreach ($recreos as $recreo): ?>
                                        <a href="?<?php 
                                            $params = ['q' => $termino, 'tipo' => $tipo, 'ver' => $recreo['id']];
                                            echo http_build_query($params);
                                        ?>" class="card result-item border <?php echo $recreoSeleccionado && $recreoSeleccionado['id'] == $recreo['id'] ? 'active' : ''; ?>">
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-2">
                                                    <i class="fas fa-map-marker-alt text-success me-1"></i>
                                                    <?php echo htmlspecialchars($recreo['nombre']); ?>
                                                </h6>
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                    <span class="badge price-badge text-white">
                                                        <?php echo formatearPrecio($recreo['precio'] ?? ''); ?>
                                                    </span>
                                                    <small class="text-muted">
                                                        <i class="fas fa-location-dot me-1"></i>
                                                        <?php echo htmlspecialchars($recreo['ubicacion'] ?? 'Ubicación no disponible'); ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Detail Panel -->
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <?php if ($recreoSeleccionado): ?>
                                <!-- Header -->
                                <div class="border-bottom pb-3 mb-4">
                                    <h1 class="h2 mb-3"><?php echo htmlspecialchars($recreoSeleccionado['nombre']); ?></h1>
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            <i class="fas fa-tag me-1"></i>
                                            <?php echo formatearPrecio($recreoSeleccionado['precio'] ?? ''); ?>
                                        </span>
                                        <span class="text-muted">
                                            <i class="fas fa-location-dot me-1"></i>
                                            <?php echo htmlspecialchars($recreoSeleccionado['ubicacion'] ?? 'Ubicación no disponible'); ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Información Básica -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="card info-card border">
                                            <div class="card-body">
                                                <div class="text-muted mb-2">
                                                    <i class="far fa-clock fa-lg"></i>
                                                </div>
                                                <small class="text-uppercase fw-bold text-muted d-block mb-1">Horario de Atención</small>
                                                <div class="fw-medium"><?php echo htmlspecialchars($recreoSeleccionado['horario'] ?? 'No especificado'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card info-card border">
                                            <div class="card-body">
                                                <div class="text-muted mb-2">
                                                    <i class="fas fa-phone fa-lg"></i>
                                                </div>
                                                <small class="text-uppercase fw-bold text-muted d-block mb-1">Contacto</small>
                                                <div class="fw-medium"><?php echo htmlspecialchars($recreoSeleccionado['telefono'] ?? 'No disponible'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card info-card border">
                                            <div class="card-body">
                                                <div class="text-muted mb-2">
                                                    <i class="fas fa-road fa-lg"></i>
                                                </div>
                                                <small class="text-uppercase fw-bold text-muted d-block mb-1">Dirección</small>
                                                <div class="fw-medium"><?php echo htmlspecialchars($recreoSeleccionado['direccion'] ?? 'No disponible'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($recreoSeleccionado['referencia'])): ?>
                                    <div class="col-md-6">
                                        <div class="card info-card border">
                                            <div class="card-body">
                                                <div class="text-muted mb-2">
                                                    <i class="fas fa-compass fa-lg"></i>
                                                </div>
                                                <small class="text-uppercase fw-bold text-muted d-block mb-1">Referencia</small>
                                                <div class="fw-medium"><?php echo htmlspecialchars($recreoSeleccionado['referencia']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Servicios -->
                                <div class="mb-4">
                                    <h5 class="mb-3">
                                        <i class="fas fa-concierge-bell me-2"></i>
                                        Servicios Disponibles
                                    </h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php 
                                        $servicios = obtenerServicios($recreoSeleccionado);
                                        if (empty($servicios)): 
                                        ?>
                                            <span class="badge bg-light text-dark border px-3 py-2">Servicios no especificados</span>
                                        <?php else: ?>
                                            <?php foreach ($servicios as $servicio): ?>
                                                <span class="badge bg-light text-dark border px-3 py-2">
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    <?php echo htmlspecialchars($servicio); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Acciones -->
                                <div class="row g-2">
                                    <?php if (!empty($recreoSeleccionado['telefono']) && $recreoSeleccionado['telefono'] != 'No disponible'): ?>
                                    <div class="col-md-6">
                                        <a href="tel:<?php echo htmlspecialchars($recreoSeleccionado['telefono']); ?>" class="btn btn-primary w-100 py-2">
                                            <i class="fas fa-phone me-2"></i>Llamar Ahora
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($recreoSeleccionado['url_maps'])): ?>
                                    <div class="col-md-6">
                                        <a href="<?php echo htmlspecialchars($recreoSeleccionado['url_maps']); ?>" target="_blank" class="btn btn-success w-100 py-2">
                                            <i class="fas fa-map-marked-alt me-2"></i>Ver en Google Maps
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <div class="col-md-6">
                                        <a href="https://maps.google.com?q=<?php echo urlencode(($recreoSeleccionado['ubicacion'] ?? '') . ' ' . ($recreoSeleccionado['direccion'] ?? '')); ?>" 
                                           target="_blank" class="btn btn-outline-primary w-100 py-2">
                                            <i class="fas fa-directions me-2"></i>Cómo Llegar
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                                    <h5>Selecciona un recreo</h5>
                                    <p>Haz clic en cualquier recreo de la lista para ver información detallada</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer-custom text-center py-4 mt-5">
        <p class="mb-0">© 2024 API Recreos Huanta - Sistema Oficial de Directorio Recreativo</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>