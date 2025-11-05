/**
 * Sistema Cliente API - JavaScript Principal
 * Funciones generales del sistema
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeSystem();
});

/**
 * Inicializar el sistema
 */
function initializeSystem() {
    initializeAlerts();
    initializeFormValidation();
    initializeTableSearch();
    initializeAnimations();
    initializeUtilities();
    
    console.log('Sistema Cliente API inicializado correctamente');
}

/**
 * Manejo de alertas
 */
function initializeAlerts() {
    // Auto-cerrar alertas después de 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        });
    }, 5000);
}

/**
 * Validación de formularios
 */
function initializeFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Scroll al primer campo con error
                const firstInvalidField = form.querySelector('.form-control:invalid, .form-select:invalid');
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalidField.focus();
                }
            }
            form.classList.add('was-validated');
        });
    });
}

/**
 * Búsqueda en tablas
 */
function initializeTableSearch() {
    const searchInput = document.querySelector('#table-search');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');
                let visibleCount = 0;
                
                rows.forEach(function(row) {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(filter);
                    row.style.display = isVisible ? '' : 'none';
                    
                    if (isVisible) visibleCount++;
                });
                
                // Actualizar contador de resultados
                updateSearchResults(visibleCount, rows.length);
                
            }, 300);
        });

        // Limpiar búsqueda con Escape
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.dispatchEvent(new Event('keyup'));
            }
        });
    }
}

/**
 * Actualizar resultados de búsqueda
 */
function updateSearchResults(visible, total) {
    let counter = document.querySelector('.search-results-counter');
    if (!counter) {
        counter = document.createElement('small');
        counter.className = 'search-results-counter text-muted mt-2 d-block';
        const searchInput = document.querySelector('#table-search');
        if (searchInput && searchInput.parentNode) {
            searchInput.parentNode.insertAdjacentElement('afterend', counter);
        }
    }
    
    if (visible < total) {
        counter.textContent = `Mostrando ${visible} de ${total} registros`;
        counter.style.display = 'block';
    } else {
        counter.style.display = 'none';
    }
}

/**
 * Animaciones
 */
function initializeAnimations() {
    // Animaciones para cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Efectos hover para botones
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
}

/**
 * Utilidades generales
 */
function initializeUtilities() {
    // Click en elementos con data-href
    document.querySelectorAll('[data-href]').forEach(element => {
        element.style.cursor = 'pointer';
        element.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });

    // Confirmación para acciones importantes
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', function(e) {
            const message = this.dataset.confirm || '¿Está seguro de realizar esta acción?';
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Toggle para contraseñas
    document.querySelectorAll('[data-toggle-password]').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const target = document.querySelector(targetId);
            const icon = this.querySelector('i');
            
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
}

/**
 * Copiar token al portapapeles
 */
function copyToken(token) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(token).then(() => {
            showNotification('Token copiado al portapapeles', 'success', 2000);
        }).catch(() => {
            fallbackCopyToClipboard(token);
        });
    } else {
        fallbackCopyToClipboard(token);
    }
}

/**
 * Fallback para copiar al portapapeles
 */
function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showNotification('Token copiado al portapapeles', 'success', 2000);
    } catch (err) {
        showNotification('Error al copiar el token', 'danger', 2000);
    }
    
    document.body.removeChild(textArea);
}

/**
 * Mostrar notificación
 */
function showNotification(message, type = 'info', duration = 5000) {
    // Usar alertas de Bootstrap si están disponibles
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = `
        top: 20px; 
        right: 20px; 
        z-index: 9999; 
        min-width: 300px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    `;
    
    const iconClass = {
        'success': 'bi-check-circle',
        'danger': 'bi-exclamation-triangle',
        'warning': 'bi-exclamation-triangle',
        'info': 'bi-info-circle'
    };
    
    alertDiv.innerHTML = `
        <i class="bi ${iconClass[type] || 'bi-info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-remover después del tiempo especificado
    setTimeout(() => {
        if (alertDiv.parentNode) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
            bsAlert.close();
        }
    }, duration);

    return alertDiv;
}

/**
 * Loading state para botones
 */
function setButtonLoading(button, loading = true) {
    if (loading) {
        button.disabled = true;
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
    } else {
        button.disabled = false;
        button.innerHTML = button.dataset.originalText || button.innerHTML;
    }
}

/**
 * Validar formulario antes del envío
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

/**
 * Formatear fecha
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-PE', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Debounce function para búsquedas
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Funciones globales para uso en todo el sistema
window.apiSystemUtils = {
    showNotification,
    copyToken,
    setButtonLoading,
    validateForm,
    formatDate,
    debounce
};