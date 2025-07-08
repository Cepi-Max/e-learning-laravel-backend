<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Tombol Toggle (untuk layar kecil) -->
<button id="sidebarToggle" class="btn btn-success m-3 d-md-none"
    style="z-index: 1100; position: fixed; top: 10px; left: 10px;">
    <i class="fas fa-bars"></i> Menu
</button>

<!-- Sidebar -->
<nav id="sidebar" class="sidebar d-flex flex-column text-white shadow-lg px-3 pt-4"
    style="min-height: 100vh; width: 260px; position: fixed; top: 0; left: -260px; z-index: 1050; background: linear-gradient(180deg, #1e3c1e, #142814, #0a1a0a); transition: all 0.3s ease-in-out;">

    <!-- Tombol close sidebar (hanya tampil di mobile) -->
    <button onclick="closeSidebar()"
        class="btn-close btn-close-white position-absolute top-0 end-0 m-3 d-md-none"></button>

    <!-- Header Sidebar -->
    <div class="sidebar-header d-flex flex-column align-items-center mb-4">
        <div class="logo-circle mb-2">
            <i class="fas fa-seedling fa-2x" style="color: #6baf54;"></i>
        </div>
        <h4 class="fw-bold mb-0 text-white">DATA PADI</h4>
        <small class="text-muted">Manajemen Pertanian</small>
    </div>

    <!-- Menu -->
    <ul class="nav flex-column mt-3 flex-grow-1">
        <li class="nav-item mb-2">
            <a href="{{ route('show.dashboard') }}"
                class="nav-link d-flex align-items-center sidebar-link {{ request()->routeIs('show.dashboard') ? 'active' : '' }}">
                <i class="me-3 fs-5 fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin.users.index') }}"
                class="nav-link d-flex align-items-center sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="me-3 fs-5 fas fa-users"></i>
                <span>Pengguna</span>
            </a>
        </li>
    </ul>

    <!-- Footer Sidebar -->
    <div class="text-center mt-auto pt-4 border-top border-secondary text-light">
        <small class="d-block text-muted">Versi 1.0</small>
        <small class="text-muted">&copy; {{ date('Y') }} Data Padi</small>
    </div>
</nav>

<!-- Overlay (klik di luar sidebar) -->
<div id="sidebarOverlay"
    style="position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background-color: rgba(0, 0, 0, 0.5); z-index: 1040; display: none;">
</div>

<!-- Style Sidebar -->
<style>
    .logo-circle {
        width: 60px;
        height: 60px;
        background-color: rgba(107, 175, 84, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease-in-out;
    }

    .logo-circle:hover {
        background-color: rgba(107, 175, 84, 0.15);
        transform: rotate(5deg);
    }

    .sidebar-link {
        color: #ffffff;
        padding: 12px 18px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
    }

    .sidebar-link:hover {
        background-color: rgba(255, 255, 255, 0.08);
        transform: translateX(6px);
    }

    .sidebar-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        font-weight: bold;
        border-left: 4px solid #6baf54;
    }

    .sidebar-header h4 {
        font-size: 1.4rem;
    }

    .sidebar-header small {
        font-size: 0.85rem;
        color: #aaaaaa;
    }

    @media (min-width: 768px) {
        #sidebar {
            left: 0 !important;
        }

        #sidebarOverlay {
            display: none !important;
        }

        #sidebarToggle {
            display: none;
        }
    }
</style>

<!-- Script Sidebar -->
<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebarToggle.addEventListener('click', () => {
        sidebar.style.left = '0';
        overlay.style.display = 'block';
    });

    overlay.addEventListener('click', () => {
        sidebar.style.left = '-260px';
        overlay.style.display = 'none';
    });

    function closeSidebar() {
        sidebar.style.left = '-260px';
        overlay.style.display = 'none';
    }
</script>