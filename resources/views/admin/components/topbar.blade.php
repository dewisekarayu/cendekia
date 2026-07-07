<div class="admin-topbar">
    <div class="admin-topbar-left">
        <button class="btn btn-sm btn-outline-secondary d-lg-none" data-sidebar-toggle>
            <i class="bi bi-list"></i>
        </button>
        <input type="text" class="admin-topbar-search d-none d-md-block" placeholder="Cari data...">
    </div>

    <div class="admin-topbar-right">

        <div class="topbar-notif">
            <i class="bi bi-bell-fill"></i>
            <span class="dot"></span>
        </div>

        <div class="dropdown">
            <div class="admin-topbar-profile" data-bs-toggle="dropdown">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ auth()->user()->name }}"
                     alt="{{ auth()->user()->name }}">
                <div class="admin-topbar-profile-text d-none d-sm-block">
                    <div class="role text-uppercase">Admin Profile</div>
                    <div class="name">{{ auth()->user()->name }}</div>
                </div>
                <i class="bi bi-chevron-down"></i>
            </div>

            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profil</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Pengaturan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
