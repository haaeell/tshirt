<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Toko Delapan')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-light: rgba(255, 255, 255, 0.85);
            --text-light: #333;
            --link-light: #555;
            --accent-light: #0d6efd;

            --bg-dark: rgba(20, 20, 20, 0.8);
            --text-dark: #f5f5f5;
            --link-dark: #bbb;
            --accent-dark: #66b2ff;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #fafafa;
            color: var(--text-light);
            transition: background 0.4s, color 0.4s;
        }

        body.dark {
            background-color: #111;
            color: var(--text-dark);
        }

        /* Navbar */
        .navbar {
            background: var(--bg-light) !important;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease-in-out;
        }

        body.dark .navbar {
            background: var(--bg-dark) !important;
        }

        .navbar.scrolled {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--accent-light) !important;
            transition: color 0.3s;
        }

        body.dark .navbar-brand {
            color: var(--accent-dark) !important;
        }

        .navbar .nav-link {
            font-weight: 500;
            color: var(--link-light) !important;
            position: relative;
            transition: color 0.3s ease-in-out;
        }

        body.dark .navbar .nav-link {
            color: var(--link-dark) !important;
        }

        .navbar .nav-link::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--accent-light);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        body.dark .navbar .nav-link::after {
            background: var(--accent-dark);
        }

        .navbar .nav-link:hover::after,
        .navbar .nav-link.active::after {
            width: 70%;
        }

        .navbar .nav-link.active {
            color: var(--accent-light) !important;
        }

        body.dark .navbar .nav-link.active {
            color: var(--accent-dark) !important;
        }

        .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            animation: fadeIn 0.2s ease;
        }

        body.dark .dropdown-menu {
            background: #222;
            color: #fff;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Toggle theme button */
        #themeToggle {
            cursor: pointer;
            border: none;
            background: transparent;
            font-size: 1.3rem;
            color: var(--link-light);
            transition: transform 0.4s ease, color 0.3s;
        }

        body.dark #themeToggle {
            color: var(--link-dark);
        }

        #themeToggle.rotate {
            transform: rotate(360deg);
        }

        /* Footer */
        footer {
            border-top: 1px solid #e9ecef;
            background: #fff;
            transition: background 0.4s, color 0.4s;
        }

        body.dark footer {
            background: #1b1b1b;
            border-color: #333;
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

        body.dark footer p {
            color: #aaa;
        }

        footer img {
            border-radius: 10px;
            transition: transform 0.4s ease;
        }

        footer img:hover {
            transform: scale(1.03);
        }

        .btn-primary {
            position: relative;
            overflow: hidden;
            border: none;
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.3px;
            background: linear-gradient(135deg, #0d6efd, #3b8efc);
            border-radius: 50px;
            padding: 0.6rem 1.4rem;
            transition: all 0.35s ease;
            box-shadow: 0 3px 10px rgba(13, 110, 253, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.45);
            background: linear-gradient(135deg, #3b8efc, #0d6efd);
        }

        .btn-primary:active {
            transform: scale(0.96);
            box-shadow: 0 3px 6px rgba(13, 110, 253, 0.4);
        }

        .btn-primary::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            transform: skewX(-20deg);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 120%;
        }

        /* Dark mode adaptation */
        body.dark .btn-primary {
            background: linear-gradient(135deg, #66b2ff, #007bff);
            box-shadow: 0 3px 10px rgba(102, 178, 255, 0.2);
        }

        body.dark .btn-primary:hover {
            box-shadow: 0 8px 20px rgba(102, 178, 255, 0.4);
            background: linear-gradient(135deg, #007bff, #66b2ff);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">TOKO DELAPAN</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}"
                            href="{{ route('welcome') }}">Home</a></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('produk') ? 'active' : '' }}"
                            href="{{ route('users.produk.index') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}"
                            href="{{ route('tentang') }}">Tentang</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}"
                            href="{{ route('chat.index') }}">Chat</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}"
                            href="{{ route('kontak') }}">Kontak</a>
                    </li>

                    @guest
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('users.cart.index') }}">🛒 Keranjang</a></li>
                                <li><a class="dropdown-item" href="{{ route('users.orders.index') }}">📦 Pesanan Saya</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪
                                        Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf</form>
                                </li>
                            </ul>
                        </li>
                    @endguest

                    <li class="nav-item ms-3">
                        <button id="themeToggle" title="Toggle theme">
                            <i class="bi bi-sun-fill" id="themeIcon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main -->
    <main class="container my-5 min-vh-100">
        @yield('content')
    </main>

    @if (Auth::check() && Auth::user()->role === 'customer')
        @php
            $auth = Auth::user();
            $admin = \App\Models\User::where('role', 'admin')->first();
        @endphp
        @include('components.chat-widget', ['auth' => $auth, 'admin' => $admin])
    @endif


    <!-- Footer -->
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-md-6">
                    <h5>Alamat Kami</h5>
                    <p>📍 Jl. Merdeka No. 88, Jakarta, Indonesia</p>
                    <p>📞 +62 812-3456-7890</p>
                    <p>📧 toko8@gmail.com</p>
                    <p class="mt-3">Jam Operasional: <br> Senin - Sabtu, 09.00 - 18.00</p>
                </div>
                <div class="col-md-6 text-center">
                    <h5>Map Lokasi</h5>
                    <img src="https://placehold.co/600x400?text=Lokasi+Toko" class="img-fluid shadow-sm" alt="Map">
                </div>
            </div>

            <hr class="my-4">
            <div class="text-center small text-muted">
                © {{ date('Y') }} Toko Delapan. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        $('#chat-btn').on('click', () => {
            $('#chat-modal').modal('show');

            $.get("{{ url('/chat') }}/1", function(data) {
                chatBox.html('');
                data.messages.forEach(msg => {
                    const align = msg.sender_id == {{ Auth::id() }} ? 'chat-user ms-auto' :
                        'chat-admin me-auto';
                    chatBox.append(`<div class="chat-bubble ${align}">${msg.message}</div>`);
                });
                chatBox.scrollTop(chatBox[0].scrollHeight);
            });
        });

        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });

        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');

        function setTheme(dark) {
            if (dark) {
                document.body.classList.add('dark');
                themeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark');
                themeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
                localStorage.setItem('theme', 'light');
            }
            themeToggle.classList.add('rotate');
            setTimeout(() => themeToggle.classList.remove('rotate'), 400);
        }

        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const storedTheme = localStorage.getItem('theme');
        setTheme(storedTheme ? storedTheme === 'dark' : prefersDark);

        themeToggle.addEventListener('click', () => {
            setTheme(!document.body.classList.contains('dark'));
        });
    </script>

    @if (session('success') || session('error'))
        <script>
            $(document).ready(function() {
                var successMessage = "{{ session('success') }}";
                var errorMessage = "{{ session('error') }}";

                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: successMessage,
                    });
                }

                if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                }
            });
        </script>
    @endif

    @stack('scripts')


</body>

</html>
