<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;" id="globalToastContainer">
        <!-- Toasts will be appended here via JS -->
    </div>
</div>

<script>
    /**
     * Tampilkan Bootstrap 5 Toast
     * @param {string} message Pesan yang akan ditampilkan
     * @param {string} type Tipe toast: 'success', 'danger', 'warning', 'info'
     */
    function showToast(message, type = 'success') {
        const container = document.getElementById('globalToastContainer');
        if (!container) return;

        // Map type to icon and border/bg colors for premium look
        let icon = 'bi-check-circle-fill text-success';
        let headerText = 'Sukses';
        let bgClass = 'bg-white';
        let borderClass = 'border-success';
        
        if (type === 'danger' || type === 'error') {
            icon = 'bi-x-circle-fill text-danger';
            headerText = 'Error';
            type = 'danger';
            borderClass = 'border-danger';
        } else if (type === 'warning') {
            icon = 'bi-exclamation-triangle-fill text-warning';
            headerText = 'Peringatan';
            borderClass = 'border-warning';
        } else if (type === 'info') {
            icon = 'bi-info-circle-fill text-info';
            headerText = 'Informasi';
            borderClass = 'border-info';
        }

        const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
        
        const toastHTML = `
            <div id="${toastId}" class="toast shadow-sm mb-3 ${borderClass} border-start border-4 ${bgClass}" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 0.75rem;">
                <div class="toast-header border-bottom-0 bg-transparent pb-0">
                    <i class="bi ${icon} me-2 fs-5"></i>
                    <strong class="me-auto text-dark">${headerText}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body pt-1 pb-3 text-secondary">
                    ${message}
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = document.getElementById(toastId);
        
        function showToastInstance() {
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 4000
            });
            toast.show();
            
            toastElement.addEventListener('hidden.bs.toast', function () {
                toastElement.remove();
            });
        }

        if (typeof bootstrap !== 'undefined') {
            showToastInstance();
        } else {
            // Load Bootstrap CSS and JS dynamically if not present
            if (!document.getElementById('bootstrap-css-dynamic')) {
                const link = document.createElement('link');
                link.id = 'bootstrap-css-dynamic';
                link.rel = 'stylesheet';
                link.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';
                document.head.appendChild(link);
            }
            if (!document.getElementById('bootstrap-js-dynamic')) {
                const script = document.createElement('script');
                script.id = 'bootstrap-js-dynamic';
                script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
                script.onload = showToastInstance;
                document.head.appendChild(script);
            } else {
                // Wait a bit for the script to load
                setTimeout(showToastInstance, 500);
            }
        }
    }

    // Override alert() bawaan browser
    window.originalAlert = window.alert;
    window.alert = function(message) {
        showToast(message, 'info');
    };

    // Automatically show toast for session messages and validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            setTimeout(() => showToast("{{ session('success') }}", 'success'), 100);
        @endif
        @if(session('error'))
            setTimeout(() => showToast("{{ session('error') }}", 'danger'), 100);
        @endif
        @if($errors->any())
            @foreach($errors->all() as $error)
                setTimeout(() => showToast("{{ $error }}", 'danger'), 100);
            @endforeach
        @endif
    });
</script>
