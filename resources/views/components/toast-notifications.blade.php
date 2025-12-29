<!-- Toast Notification Container -->
<div id="toast-container" style="position: fixed; top: 80px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; max-width: 420px;"></div>

<script>
/**
 * Modern Toast Notification System
 * Replaces alert() with elegant, non-intrusive notifications
 */
const Toast = {
    /**
     * Show a toast notification
     * @param {string} message - The message to display
     * @param {string} type - Type: 'success', 'error', 'warning', 'info'
     * @param {number} duration - Duration in milliseconds (default: 5000)
     */
    show(message, type = 'info', duration = 5000) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        // Toast styles
        const styles = {
            success: { bg: 'linear-gradient(135deg, #10b981 0%, #059669 100%)', icon: this.getIcon('success') },
            error: { bg: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)', icon: this.getIcon('error') },
            warning: { bg: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)', icon: this.getIcon('warning') },
            info: { bg: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)', icon: this.getIcon('info') }
        };

        const style = styles[type] || styles.info;

        toast.style.cssText = `
            background: ${style.bg};
            color: white;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 320px;
            max-width: 420px;
            animation: slideIn 0.3s ease-out, fadeOut 0.3s ease-in ${duration - 300}ms;
            cursor: pointer;
            transition: transform 0.2s;
        `;

        toast.innerHTML = `
            <div style="flex-shrink: 0;">
                ${style.icon}
            </div>
            <div style="flex: 1; font-size: 14px; font-weight: 500; line-height: 1.5;">
                ${this.escapeHtml(message)}
            </div>
            <button onclick="this.parentElement.remove()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background 0.2s;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;

        // Add hover effect
        toast.onmouseover = () => toast.style.transform = 'translateX(-4px)';
        toast.onmouseout = () => toast.style.transform = 'translateX(0)';

        // Click to dismiss
        toast.onclick = (e) => {
            if (e.target.tagName !== 'BUTTON') {
                toast.remove();
            }
        };

        container.appendChild(toast);

        // Auto remove after duration
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, duration);
    },

    success(message, duration = 5000) {
        this.show(message, 'success', duration);
    },

    error(message, duration = 6000) {
        this.show(message, 'error', duration);
    },

    warning(message, duration = 5500) {
        this.show(message, 'warning', duration);
    },

    info(message, duration = 5000) {
        this.show(message, 'info', duration);
    },

    getIcon(type) {
        const icons = {
            success: `<svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`,
            error: `<svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`,
            warning: `<svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>`,
            info: `<svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`
        };
        return icons[type] || icons.info;
    },

    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
};

// Add CSS animations
if (!document.getElementById('toast-animations')) {
    const style = document.createElement('style');
    style.id = 'toast-animations';
    style.innerHTML = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
        #toast-container .toast button:hover {
            background: rgba(255,255,255,0.3) !important;
        }
    `;
    document.head.appendChild(style);
}

// Make Toast available globally
window.Toast = Toast;

// Override window.alert (optional - for gradual migration)
window.showAlert = function(message) {
    console.warn('Using deprecated alert(). Please use Toast.info() instead.');
    Toast.info(message);
};
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Toast.success('{{ session('success') }}');
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Toast.error('{{ session('error') }}');
    });
</script>
@endif

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Toast.warning('{{ session('warning') }}');
    });
</script>
@endif

@if(session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Toast.info('{{ session('info') }}');
    });
</script>
@endif

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($errors->all() as $error)
            Toast.error('{{ $error }}');
        @endforeach
    });
</script>
@endif
