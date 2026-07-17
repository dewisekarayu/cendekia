<!-- Menggunakan w-80 untuk ruang sidebar yang jauh lebih luas -->
<aside class="admin-sidebar w-80 bg-[#002B6B] text-white/75 h-screen flex flex-col p-4 border-r border-white/10 select-none" id="adminSidebar">
    
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <!-- Bagian Logo Atas (Tetap BOLD sesuai request) -->
    <div class="logo flex items-center gap-3 pb-4 mb-3 border-b border-white/10 flex-shrink-0">
        <i class="bi bi-mortarboard-fill text-2xl text-white"></i>
        <div>
            <span class="text-base font-bold text-white block leading-tight">Cendekia Admin</span>
            <span class="text-[9.5px] font-normal tracking-wider text-white/50 block mt-0.5">LIVE ADMINISTRATION</span>
        </div>
    </div>

    <!-- Nav Menu (font-bold/semibold/medium diubah ke font-normal) -->
    <nav class="nav-menu flex flex-col gap-1 overflow-y-auto no-scrollbar flex-1">
        <!-- Dashboard -->
        <div class="nav-section-title text-[12px] font-normal tracking-wider text-white/40 pb-1 px-2.5 uppercase">
            Menu Utama
        </div>
        <div class="nav-item">
            <a href="<?php echo e(route('admin.dashboard')); ?>"
               class="nav-link flex items-center gap-3 px-3 py-2 text-sm font-normal rounded-lg transition-all duration-200 decoration-none
               <?php echo e(request()->routeIs('admin.dashboard') 
                  ? 'bg-white/15 text-white shadow-sm' 
                  : 'text-white/70 hover:bg-white/10 hover:text-white'); ?>">
                <i class="bi bi-speedometer2 text-base w-5 flex justify-center flex-shrink-0"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Master Data -->
        <div class="nav-section-title text-[10px] font-normal tracking-wider text-white/40 pb-1 px-2.5 uppercase">
            Master Data
        </div>
        <div class="nav-item">
            <a href="<?php echo e(route('admin.dosen.index')); ?>"
               class="nav-link flex items-center gap-3 px-3 py-2 text-sm font-normal rounded-lg transition-all duration-200 decoration-none
               <?php echo e(request()->routeIs('admin.dosen.*') 
                  ? 'bg-white/15 text-white shadow-sm' 
                  : 'text-white/70 hover:bg-white/10 hover:text-white'); ?>">
                <i class="bi bi-person-badge text-base w-5 flex justify-center flex-shrink-0"></i>
                <span>Data Dosen</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="<?php echo e(route('admin.mahasiswa.index')); ?>"
               class="nav-link flex items-center gap-3 px-3 py-2 text-sm font-normal rounded-lg transition-all duration-200 decoration-none
               <?php echo e(request()->routeIs('admin.mahasiswa.*') 
                  ? 'bg-white/15 text-white shadow-sm' 
                  : 'text-white/70 hover:bg-white/10 hover:text-white'); ?>">
                <i class="bi bi-people-fill text-base w-5 flex justify-center flex-shrink-0"></i>
                <span>Data Mahasiswa</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="<?php echo e(route('admin.mata-kuliah.index')); ?>"
               class="nav-link flex items-center gap-3 px-3 py-2 text-sm font-normal rounded-lg transition-all duration-200 decoration-none
               <?php echo e(request()->routeIs('admin.mata-kuliah.*') 
                  ? 'bg-white/15 text-white shadow-sm' 
                  : 'text-white/70 hover:bg-white/10 hover:text-white'); ?>">
                <i class="bi bi-book-fill text-base w-5 flex justify-center flex-shrink-0"></i>
                <span>Mata Kuliah</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="<?php echo e(route('admin.program-studi.index')); ?>"
               class="nav-link flex items-center gap-3 px-3 py-2 text-sm font-normal rounded-lg transition-all duration-200 decoration-none
               <?php echo e(request()->routeIs('admin.program-studi.*') 
                  ? 'bg-white/15 text-white shadow-sm' 
                  : 'text-white/70 hover:bg-white/10 hover:text-white'); ?>">
                <i class="bi bi-diagram-3 text-base w-5 flex justify-center flex-shrink-0"></i>
                <span>Program Studi</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="<?php echo e(route('admin.kalender-akademik.index')); ?>"
               class="nav-link flex items-center gap-3 px-3 py-2 text-sm font-normal rounded-lg transition-all duration-200 decoration-none
               <?php echo e(request()->routeIs('admin.kalender-akademik.*') 
                  ? 'bg-white/15 text-white shadow-sm' 
                  : 'text-white/70 hover:bg-white/10 hover:text-white'); ?>">
                <i class="bi bi-calendar3 text-base w-5 flex justify-center flex-shrink-0"></i>
                <span>Kalender Akademik</span>
            </a>
        </div>

        <!-- Konten -->
        <div class="nav-section-title text-[10px] font-normal tracking-wider text-white/40 px-2.5 uppercase">
            Konten
        </div>
        <div class="nav-item">
            <a href="<?php echo e(route('admin.pengumuman.index')); ?>"
               class="nav-link flex items-center gap-3 px-3 py-2 text-sm font-normal rounded-lg transition-all duration-200 decoration-none
               <?php echo e(request()->routeIs('admin.pengumuman.*') 
                  ? 'bg-white/15 text-white shadow-sm' 
                  : 'text-white/70 hover:bg-white/10 hover:text-white'); ?>">
                <i class="bi bi-megaphone-fill text-base w-5 flex justify-center flex-shrink-0"></i>
                <span>Pengumuman</span>
            </a>
        </div>

        <!-- Support -->
        <div class="nav-section-title text-[10px] font-normal tracking-wider text-white/40 px-2.5 uppercase">
            Support
        </div>
        <div class="nav-item">
            <a href="<?php echo e(route('admin.help-center.dashboard')); ?>"
               class="nav-link flex items-center gap-3 px-3 py-2 text-sm font-normal rounded-lg transition-all duration-200 decoration-none
               <?php echo e(request()->routeIs('admin.help-center.*') 
                  ? 'bg-white/15 text-white shadow-sm' 
                  : 'text-white/70 hover:bg-white/10 hover:text-white'); ?>">
                <i class="bi bi-question-circle-fill text-base w-5 flex justify-center flex-shrink-0"></i>
                <span>Pusat Bantuan</span>
            </a>
        </div>
    </nav>

    <!-- Bagian bawah: Keluar -->
    <div class="pt-2 border-t border-white/10 flex-shrink-0 mt-auto">
        <div class="nav-item">
            <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form" class="m-0">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-link w-full flex items-center gap-3 px-3 py-2 text-sm font-normal rounded-lg transition-all duration-200 text-white/70 hover:bg-red-500/10 hover:text-red-400 border-none bg-transparent cursor-pointer text-left">
                    <i class="bi bi-box-arrow-right text-base w-5 flex justify-center flex-shrink-0"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside><?php /**PATH C:\laragon\www\cendekia\resources\views/admin/components/sidebar.blade.php ENDPATH**/ ?>