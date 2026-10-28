<div aria-live="polite" aria-atomic="true" class="fixed top-5 right-5 z-[9999] pointer-events-none flex flex-col gap-3 max-w-sm w-full px-4 sm:px-0" id="globalToastContainer">
    <!-- Toasts will be appended here via JS -->
</div>

<script>
    /**
     * Tampilkan Toast Notification Bergaya Modern (Tailwind CSS murni, tanpa dependensi Bootstrap CSS yang merusak layout)
     * @param {string} message Pesan yang akan ditampilkan
     * @param {string} type Tipe toast: 'success', 'danger', 'error', 'warning', 'info'
     */
    function showToast(message, type = 'success') {
        const container = document.getElementById('globalToastContainer');
        if (!container) return;

        let icon = `<svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
        let headerText = 'Sukses';
        let borderColor = 'border-emerald-500';
        let badgeBg = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-350';

        if (type === 'danger' || type === 'error') {
            icon = `<svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
            headerText = 'Error';
            borderColor = 'border-rose-500';
            badgeBg = 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-350';
        } else if (type === 'warning') {
            icon = `<svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`;
            headerText = 'Peringatan';
            borderColor = 'border-amber-500';
            badgeBg = 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-350';
        } else if (type === 'info') {
            icon = `<svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
            headerText = 'Informasi';
            borderColor = 'border-blue-500';
            badgeBg = 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-350';
        }

        const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);

        const toastHTML = `
            <div id="${toastId}" class="pointer-events-auto transform transition-all duration-300 ease-out translate-y-[-10px] opacity-0 flex flex-col w-full bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200/80 dark:border-slate-700 border-l-4 ${borderColor} p-3.5 text-xs">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        ${icon}
                        <strong class="font-bold text-slate-800 dark:text-white">${headerText}</strong>
                    </div>
                    <button type="button" onclick="document.getElementById('${toastId}')?.remove()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition p-1 rounded-lg">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="mt-1.5 text-slate-600 dark:text-slate-300 font-medium pl-7 break-words">
                    ${message}
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = document.getElementById(toastId);

        // Animate in
        requestAnimationFrame(() => {
            if (toastElement) {
                toastElement.classList.remove('translate-y-[-10px]', 'opacity-0');
                toastElement.classList.add('translate-y-0', 'opacity-100');
            }
        });

        // Auto dismiss after 4 seconds
        setTimeout(() => {
            if (toastElement && toastElement.parentNode) {
                toastElement.classList.add('opacity-0', 'translate-y-[-10px]');
                setTimeout(() => toastElement.remove(), 300);
            }
        }, 4000);
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
