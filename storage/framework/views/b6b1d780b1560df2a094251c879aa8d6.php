<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon',
    'color' => 'blue',
    'label',
    'value',
    'change' => null,   // e.g. '12%', '-3%', '0%'
]));

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

foreach (array_filter(([
    'icon',
    'color' => 'blue',
    'label',
    'value',
    'change' => null,   // e.g. '12%', '-3%', '0%'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $trend = 'flat';
    if (!is_null($change)) {
        $numeric = (float) str_replace('%', '', $change);
        $trend = $numeric > 0 ? 'up' : ($numeric < 0 ? 'down' : 'flat');
    }
?>

<div class="stat-card">
    <div class="stat-card-top">
        <div class="stat-card-icon <?php echo e($color); ?>">
            <i class="bi bi-<?php echo e($icon); ?>"></i>
        </div>

        <?php if(!is_null($change)): ?>
            <span class="stat-card-badge <?php echo e($trend); ?>">
                <?php if($trend === 'up'): ?>
                    <i class="bi bi-arrow-up-short"></i>
                <?php elseif($trend === 'down'): ?>
                    <i class="bi bi-arrow-down-short"></i>
                <?php endif; ?>
                <?php echo e($change); ?>

            </span>
        <?php endif; ?>
    </div>

    <div class="label"><?php echo e($label); ?></div>
    <div class="number"><?php echo e($value); ?></div>
</div><?php /**PATH D:\laragon\www\cendekia\resources\views/components/stat-card.blade.php ENDPATH**/ ?>