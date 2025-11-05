<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Sistema Cliente API'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --sidebar-width: 280px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, #1d4ed8 100%);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 0.5rem;
            margin: 0 0.5rem;
            transition: all 0.2s ease;
        }
        
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 2rem;
        }
        
        .login-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="<?php echo $bodyClass ?? ''; ?>">

<?php if (isLoggedIn() && ($action ?? '') !== 'login'): ?>
<!-- Sidebar -->
<nav class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-white mb-0">
            <i class="bi bi-shield-lock me-2"></i>
            Sistema Token Api
        </h4>
    </div>
    
    <div class="sidebar-nav p-3">
        <a class="nav-link <?php echo ($action ?? '') === 'tokens' ? 'active' : ''; ?>" 
           href="<?php echo BASE_URL; ?>index.php?action=tokens">
            <i class="bi bi-key"></i>
            <span>Gestión de Tokens</span>
        </a>
    </div>
    
    <div class="sidebar-footer position-absolute bottom-0 start-0 end-0 p-3 border-top border-white-10">
        <div class="d-flex align-items-center mb-2">
            <div class="bg-white-20 rounded-circle d-flex align-items-center justify-content-center me-2" 
                 style="width: 40px; height: 40px;">
                <span class="text-white fw-bold">
                    <?php echo strtoupper(substr($_SESSION['admin_nombre'] ?? 'A', 0, 1)); ?>
                </span>
            </div>
            <div>
                <div class="text-white small fw-bold"><?php echo $_SESSION['admin_nombre'] ?? 'Admin'; ?></div>
                <div class="text-white-50 small">@<?php echo $_SESSION['admin_username'] ?? 'admin'; ?></div>
            </div>
        </div>
        <a href="<?php echo BASE_URL; ?>index.php?action=logout" class="nav-link text-white-50">
            <i class="bi bi-box-arrow-right"></i>
            <span>Cerrar Sesión</span>
        </a>
    </div>
</nav>

<!-- Main Content -->
<div class="main-content">
    <div class="content-wrapper">
        <?php 
        // Mostrar alertas
        $alert = getAlert();
        if ($alert): 
        ?>
        <div class="alert alert-<?php echo $alert['type'] === 'error' ? 'danger' : $alert['type']; ?> alert-dismissible fade show mb-4">
            <i class="bi bi-<?php echo $alert['type'] === 'success' ? 'check-circle' : ($alert['type'] === 'error' ? 'exclamation-triangle' : 'info-circle'); ?> me-2"></i>
            <?php echo $alert['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

<?php else: ?>
<!-- Para páginas de login -->
<?php 
$alert = getAlert();
if ($alert): 
?>
<div class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999;">
    <div class="alert alert-<?php echo $alert['type'] === 'error' ? 'danger' : $alert['type']; ?> alert-dismissible fade show">
        <i class="bi bi-<?php echo $alert['type'] === 'success' ? 'check-circle' : ($alert['type'] === 'error' ? 'exclamation-triangle' : 'info-circle'); ?> me-2"></i>
        <?php echo $alert['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>