<aside class="admin-sidebar" id="adminSidebar">
    <div class="logo">
        <i class="bi bi-mortarboard-fill"></i>
        <span>Cendekia</span>
    </div>

    <nav class="nav-menu">
        <!-- Dashboard Section -->
        <div class="nav-section-title">MENU UTAMA</div>
        
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Master Data Section -->
        <div class="nav-section-title">MASTER DATA</div>

        <div class="nav-item">
            <a href="{{ route('admin.dosen.index') }}" 
               class="nav-link {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                <span>Data Dosen</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.mahasiswa.index') }}" 
               class="nav-link {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Data Mahasiswa</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.mata-kuliah.index') }}" 
               class="nav-link {{ request()->routeIs('admin.mata-kuliah.*') ? 'active' : '' }}">
                <i class="bi bi-book-fill"></i>
                <span>Mata Kuliah</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.program-studi.index') }}" 
               class="nav-link {{ request()->routeIs('admin.program-studi.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3"></i>
                <span>Program Studi</span>
            </a>
        </div>

        <!-- Content Section -->
        <div class="nav-section-title">KONTEN</div>

        <div class="nav-item">
            <a href="{{ route('admin.pengumuman.index') }}" 
               class="nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone-fill"></i>
                <span>Pengumuman</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.kelas.index') }}" 
               class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                <i class="bi bi-door-open"></i>
                <span>Kelas Perkuliahan</span>
            </a>
        </div>

        <!-- Divider -->
        <hr style="border-color: rgba(255, 255, 255, 0.2); margin: 1.5rem 0;">

        <!-- Logout -->
        <div class="nav-item">
            <form method="POST" action="{{ route('logout') }}" class="w-100">
                @csrf
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>
