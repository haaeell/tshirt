<nav class="sidebar sidebar-offcanvas bg-light p-3 shadow" style="width: 250px;">
    <div class="mb-4 text-center">
        <img src="https://placehold.co/100x100" alt="Logo" class="img-fluid rounded-circle mb-2">
        <h6 class="fw-bold mb-0">Admin Panel</h6>
    </div>

    <ul class="nav flex-column">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="/home">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <!-- Produk -->
        <li class="nav-item mt-2">
            <span class="text-uppercase fw-semibold text-muted small ps-2">Produk & Variasi</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/produk">
                <i class="fas fa-box menu-icon"></i>
                <span class="menu-title">Produk</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/produk/varian">
                <i class="fas fa-ruler menu-icon"></i>
                <span class="menu-title">Varian Produk</span>
            </a>
        </li>

        <!-- Pesanan -->
        <li class="nav-item mt-3">
            <span class="text-uppercase fw-semibold text-muted small ps-2">Transaksi</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/pesanan">
                <i class="fas fa-shopping-cart menu-icon"></i>
                <span class="menu-title">Pesanan</span>
            </a>
        </li>

        <!-- Voucher & Ulasan -->
        <li class="nav-item mt-3">
            <span class="text-uppercase fw-semibold text-muted small ps-2">Promosi & Ulasan</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/voucher">
                <i class="fas fa-tags menu-icon"></i>
                <span class="menu-title">Voucher</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/ulasan">
                <i class="fas fa-star menu-icon"></i>
                <span class="menu-title">Ulasan Produk</span>
            </a>
        </li>

        <!-- Pelanggan & Admin -->
        <li class="nav-item mt-3">
            <span class="text-uppercase fw-semibold text-muted small ps-2">Pengguna</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/customers">
                <i class="fas fa-user menu-icon"></i>
                <span class="menu-title">Pelanggan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/users">
                <i class="fas fa-user-shield menu-icon"></i>
                <span class="menu-title">Admin</span>
            </a>
        </li>

        <!-- Laporan & Konten -->
        <li class="nav-item mt-3">
            <span class="text-uppercase fw-semibold text-muted small ps-2">Laporan & Konten</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/laporan">
                <i class="fas fa-file-alt menu-icon"></i>
                <span class="menu-title">Laporan Penjualan</span>
            </a>
        </li>

        <!-- Pengaturan -->
        <li class="nav-item mt-3">
            <span class="text-uppercase fw-semibold text-muted small ps-2">Lainnya</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/pengaturan">
                <i class="fas fa-cog menu-icon"></i>
                <span class="menu-title">Pengaturan</span>
            </a>
        </li>

        <!-- Logout -->
        <hr>
        <li class="nav-item">
            <a class="nav-link text-danger" href="#"
                onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                <i class="fas fa-sign-out-alt menu-icon"></i>
                <span class="menu-title">Keluar</span>
            </a>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</nav>
