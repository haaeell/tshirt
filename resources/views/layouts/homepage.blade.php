<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Delapan')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #fafafa;
            color: #333;
        }

        .navbar {
            background: #fff !important;
        }

        .navbar .nav-link {
            font-weight: 500;
            color: #555 !important;
            transition: color 0.2s;
        }

        .navbar .nav-link.active,
        .navbar .nav-link:hover {
            color: #0d6efd !important;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
        }

        footer {
            border-top: 1px solid #e9ecef;
            background: #fff;
        }

        footer h5 {
            font-size: 1rem;
            margin-bottom: .75rem;
            font-weight: 600;
        }

        footer p {
            margin: 0;
            color: #666;
        }

        .btn-primary {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.4rem 0.9rem;
        }


        .color-option input:checked+span {
            border: 2px solid #000;
            /* border hitam saat dipilih */
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px #000;
            /* biar lebih jelas */
        }

        .color-option span {
            cursor: pointer;
            display: inline-block;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">TOKO DELAPAN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('tentang') ? 'active' : '' }}"
                            href="{{ url('/tentang') }}">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.produk.*') ? 'active' : '' }}"
                            href="{{ route('users.produk.index') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kontak') ? 'active' : '' }}"
                            href="{{ url('/kontak') }}">Kontak</a>
                    </li>

                    @guest
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button"
                                data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="{{ route('users.cart.index') }}">Keranjang</a></li>
                                <li><a class="dropdown-item" href="{{ route('users.orders.index') }}">Pesanan Saya</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf</form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main -->
    <main class="container my-5 min-vh-100">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container">
            <div class="row gy-3">
                <div class="col-md-6">
                    <h5>Alamat Kami</h5>
                    <p>📍 Indonesia<br>📞 +62 812-3456-7890<br>📧 toko8@gmail.com</p>
                </div>
                <div class="col-md-6 text-center">
                    <h5>Map</h5>
                    <img src="https://placehold.co/300x200" class="img-fluid rounded shadow-sm" alt="Map">
                </div>
            </div>
            <div class="text-center mt-4 small text-muted">
                © {{ date('Y') }} Toko Delapan. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
