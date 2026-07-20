<!-- Contextual Help Box -->
<div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 p-4 sm:p-5 shadow-sm">
    <div class="flex gap-4">
        <div class="shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#002B6B] mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <?php if(isset($title)): ?>
                <p class="font-bold text-gray-900 text-sm mb-1"><?php echo e($title); ?></p>
            <?php endif; ?>
            <?php if(isset($message)): ?>
                <p class="text-sm text-gray-700 leading-relaxed"><?php echo e($message); ?></p>
            <?php endif; ?>
            <?php if(isset($tips) && is_array($tips) && count($tips) > 0): ?>
                <ul class="mt-2 space-y-1 text-xs text-gray-700">
                    <?php $__currentLoopData = $tips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-start gap-2">
                            <span class="text-[#002B6B] font-bold mt-0.5">→</span>
                            <span><?php echo e($tip); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
            <?php if(isset($links) && is_array($links) && count($links) > 0): ?>
                <div class="mt-3 flex flex-wrap gap-2">
                    <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($link['url']); ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-[#002B6B] hover:text-blue-900 transition">
                            <?php echo e($link['label']); ?>

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php if(isset($dismissible) && $dismissible): ?>
            <button onclick="this.parentElement.parentElement.remove()" class="shrink-0 text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH D:\laragon\www\cendekia\resources\views/help-center/contextual-help.blade.php ENDPATH**/ ?>