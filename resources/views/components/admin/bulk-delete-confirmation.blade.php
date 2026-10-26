@props(['type', 'label'])

@php
    $prefix = 'bulkDelete' . ucfirst($type);
    $formId = $prefix . 'Form';
    $selectAllId = 'selectAll' . ucfirst($type);
    $buttonId = $prefix . 'Button';
    $countId = 'selected' . ucfirst($type) . 'Count';
    $modalId = $prefix . 'Modal';
    $modalCountId = $prefix . 'ModalCount';
    $confirmButtonId = 'confirm' . ucfirst($prefix);
    $checkboxSelector = '.' . $type . '-select';
@endphp

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-3 border-bottom border-slate-100 dark:border-slate-700/60">
    <label class="d-inline-flex align-items-center gap-2 mb-0 text-secondary small">
        <input type="checkbox" id="{{ $selectAllId }}" class="form-check-input m-0">
        Pilih semua {{ $label }} di halaman ini
    </label>
    <form id="{{ $formId }}" action="{{ route('admin.' . $type . '.bulk-destroy') }}" method="POST">
        @csrf
        <button type="submit" id="{{ $buttonId }}" class="btn btn-danger btn-sm" disabled>
            <i class="bi bi-trash me-1"></i>
            Hapus yang dipilih (<span id="{{ $countId }}">0</span>)
        </button>
    </form>
</div>

<div class="modal fade" id="{{ $modalId }}" tabindex="-1"
     aria-labelledby="{{ $modalId }}Label" aria-describedby="{{ $modalId }}Description" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body px-4 py-4 text-center">
                <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mx-auto mb-3"
                     style="width: 60px; height: 60px;">
                    <i class="bi bi-trash3-fill fs-4"></i>
                </div>
                <h5 class="fw-bold mb-2" id="{{ $modalId }}Label" style="color: #012428;">
                    Hapus {{ $label }} terpilih?
                </h5>
                <p class="text-muted small mb-4" id="{{ $modalId }}Description">
                    <span id="{{ $modalCountId }}">0</span> {{ $label }} akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light border px-4 py-2 fw-semibold text-secondary"
                            data-bs-dismiss="modal" style="border-radius: 8px;">
                        Batal
                    </button>
                    <button type="button" id="{{ $confirmButtonId }}"
                            class="btn btn-danger px-4 py-2 fw-semibold" style="border-radius: 8px;">
                        <i class="bi bi-trash me-1"></i> Ya, hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxSelector = @json($checkboxSelector);
        const selectAll = document.getElementById(@json($selectAllId));
        const deleteForm = document.getElementById(@json($formId));
        const deleteButton = document.getElementById(@json($buttonId));
        const selectedCount = document.getElementById(@json($countId));
        const modalCount = document.getElementById(@json($modalCountId));
        const confirmButton = document.getElementById(@json($confirmButtonId));
        const deleteModal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById(@json($modalId))
        );
        let deletionConfirmed = false;

        function updateSelection() {
            const checkboxes = Array.from(document.querySelectorAll(checkboxSelector));
            const checkedCount = checkboxes.filter(checkbox => checkbox.checked).length;
            deleteButton.disabled = checkedCount === 0;
            selectedCount.textContent = checkedCount;
            selectAll.checked = checkboxes.length > 0 && checkedCount === checkboxes.length;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        }

        selectAll.addEventListener('change', function () {
            document.querySelectorAll(checkboxSelector).forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateSelection();
        });

        document.addEventListener('change', function (event) {
            if (event.target.matches(checkboxSelector)) {
                updateSelection();
            }
        });

        deleteForm.addEventListener('submit', function (event) {
            if (deletionConfirmed) {
                deletionConfirmed = false;
                return;
            }

            event.preventDefault();
            const checkedCount = document.querySelectorAll(checkboxSelector + ':checked').length;
            if (checkedCount === 0) return;

            modalCount.textContent = checkedCount;
            deleteModal.show();
        });

        confirmButton.addEventListener('click', function () {
            deletionConfirmed = true;
            deleteModal.hide();
            deleteForm.requestSubmit();
        });

        updateSelection();

        const tableContainer = document.getElementById('tableContainer');
        if (tableContainer) {
            new MutationObserver(updateSelection).observe(tableContainer, {
                childList: true,
                subtree: true
            });
        }
    });
</script>
