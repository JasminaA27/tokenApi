<?php if (isLoggedIn() && ($action ?? '') !== 'login'): ?>
    </div> <!-- content-wrapper -->
</div> <!-- main-content -->

<footer class="bg-white border-top py-3" style="margin-left: var(--sidebar-width);">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <small class="text-muted">
                    &copy; <?php echo date('Y'); ?> Sistema Cliente API
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">
                    <i class="bi bi-person me-1"></i>
                    <?php echo $_SESSION['admin_nombre'] ?? 'Admin'; ?> | 
                    <i class="bi bi-clock me-1"></i>
                    <?php echo date('d/m/Y H:i'); ?>
                </small>
            </div>
        </div>
    </div>
</footer>

<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>