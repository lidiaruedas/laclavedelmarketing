/**
 * La Clave del Marketing - JavaScript
 * 
 * Funcionalidades dinámicas de la aplicación.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar componentes
    initAlerts();
    initDeleteConfirmations();
    initMobileMenu();
    initAnimations();
});

/**
 * Auto-cerrar alertas después de unos segundos
 */
function initAlerts() {
    const alerts = document.querySelectorAll('.alert[data-auto-dismiss]');
    
    alerts.forEach(alert => {
        const duration = parseInt(alert.dataset.autoDismiss) || 5000;
        
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, duration);
    });
}

/**
 * Confirmación de eliminación
 */
function initDeleteConfirmations() {
    document.addEventListener('submit', function(e) {
        const form = e.target;
        
        if (form.classList.contains('delete-form')) {
            const message = form.dataset.confirmMessage || '¿Estás seguro de que quieres eliminar este elemento?';
            
            if (!confirm(message)) {
                e.preventDefault();
            }
        }
    });
}

/**
 * Menú móvil
 */
function initMobileMenu() {
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.createElement('div');
    
    overlay.className = 'sidebar-overlay';
    overlay.style.cssText = `
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 99;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    `;
    document.body.appendChild(overlay);
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            
            if (sidebar.classList.contains('open')) {
                overlay.style.opacity = '1';
                overlay.style.visibility = 'visible';
            } else {
                overlay.style.opacity = '0';
                overlay.style.visibility = 'hidden';
            }
        });
    }
    
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('open');
        overlay.style.opacity = '0';
        overlay.style.visibility = 'hidden';
    });
}

/**
 * Animaciones de entrada
 */
function initAnimations() {
    const animatedElements = document.querySelectorAll('[data-animate]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    animatedElements.forEach(el => observer.observe(el));
}

/**
 * Función helper para hacer peticiones AJAX
 */
async function fetchAPI(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    const response = await fetch(url, { ...defaultOptions, ...options });
    
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    return response.json();
}

/**
 * Mostrar notificación toast
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type}`;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        max-width: 400px;
        animation: fadeIn 0.3s ease;
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

/**
 * Copiar texto al portapapeles
 */
async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        showToast('Copiado al portapapeles', 'success');
    } catch (err) {
        showToast('Error al copiar', 'error');
    }
}
