<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['icon', 'color' => 'blue', 'title', 'value']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['icon', 'color' => 'blue', 'title', 'value']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="stat-card d-flex flex-row align-items-center gap-3">
    <div class="stat-card-icon <?php echo e($color); ?> shrink-0">
        <i class="bi bi-<?php echo e($icon); ?>"></i>
    </div>
    <div>
        <div class="label"><?php echo e($title); ?></div>
        <div class="number"><?php echo e($value); ?></div>
    </div>
</div>
<?php /**PATH C:\laragon\www\cendekia\resources\views/components/admin/stat-card.blade.php ENDPATH**/ ?>