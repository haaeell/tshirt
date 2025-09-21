<nav class="sidebar sidebar-offcanvas bg-light p-3" style="width: 250px;">
    <div class="mb-4 text-center">
        <img src="https://placehold.co/100x100" alt="Logo" class="img-fluid">
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-user menu-icon"></i>
                <span class="menu-title">Akun Pelanggan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-shopping-cart menu-icon"></i>
                <span class="menu-title">Pesanan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/produk">
                <i class="fas fa-box menu-icon"></i>
                <span class="menu-title">Produk</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/produk-varian">
                <i class="fas fa-cube menu-icon"></i>
                <span class="menu-title">Varian Produk</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/materials">
                <i class="fas fa-cube menu-icon"></i>
                <span class="menu-title">Bahan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/voucher">
                <i class="fas fa-tags menu-icon"></i>
                <span class="menu-title">Voucher</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-credit-card menu-icon"></i>
                <span class="menu-title">Pembayaran</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-truck menu-icon"></i>
                <span class="menu-title">Pengiriman</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-star menu-icon"></i>
                <span class="menu-title">Ulasan Produk</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-file-alt menu-icon"></i>
                <span class="menu-title">Laporan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-edit menu-icon"></i>
                <span class="menu-title">Konten</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/users">
                <i class="fas fa-database menu-icon"></i>
                <span class="menu-title">Data Admin</span>
            </a>
        </li>
        <hr>
        <li class="nav-item">
            <a class="nav-link" href="#"
                onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                <i class="fas fa-sign-out-alt menu-icon"></i>
                <span class="menu-title">Logout</span>
            </a>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</nav>
