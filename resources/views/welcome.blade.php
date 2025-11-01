@extends('layouts.homepage')

@section('content')
    <style>
        /* === Hero Section === */
        .hero {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: #fff;
            padding: 6rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.15), transparent 60%);
            z-index: 0;
        }

        .hero .container {
            position: relative;
            z-index: 1;
            animation: fadeUp 0.8s ease-in-out;
        }

        .hero h1 {
            font-weight: 800;
            font-size: 3rem;
            letter-spacing: 1px;
        }

        .hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 1.5rem;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* === Profil Section === */
        section {
            transition: background 0.3s, color 0.3s;
        }

        body.dark section {
            background-color: #181818;
            color: #f5f5f5;
        }

        .nav-pills .nav-link {
            border-radius: 50px;
            padding: 0.5rem 1.3rem;
            font-weight: 500;
            color: #555;
            transition: all 0.3s;
        }

        .nav-pills .nav-link.active {
            background-color: #0d6efd;
            color: #fff !important;
            box-shadow: 0 3px 10px rgba(13, 110, 253, 0.4);
        }

        body.dark .nav-pills .nav-link {
            color: #bbb;
        }

        body.dark .nav-pills .nav-link.active {
            background-color: #66b2ff;
            color: #111 !important;
        }

        /* === Card Produk === */
        .card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s ease;
            background: #fff;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        .card img {
            transition: transform 0.5s ease;
        }

        .card:hover img {
            transform: scale(1.05);
        }

        body.dark .card {
            background: #222;
            color: #eee;
        }

        .card-title {
            font-weight: 600;
        }

        .card-text {
            color: #0d6efd;
            font-weight: 500;
        }

        body.dark .card-text {
            color: #66b2ff;
        }

        /* === Animasi Fade In === */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="fw-bold">TOKO DELAPAN</h1>
            <p class="lead">Layanan sablon kaos, printing, dan produk kreatif lainnya. Cepat, murah, dan berkualitas!</p>
            <a href="/produk" class="btn btn-light btn-lg px-4 shadow-sm">Booking Sekarang</a>
        </div>
    </section>


    <!-- Profil Kami -->
    <section id="tentang" class="py-5">
        <div class="container text-center fade-in">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <h2 class="fw-semibold mb-3">Profil Kami</h2>
                    <p class="text-muted">Kami adalah penyedia layanan sablon profesional dengan berbagai pilihan produk.
                        Temukan layanan terbaik untuk kebutuhan Anda — cepat, tepat, dan kreatif!</p>
                </div>
            </div>
        </div>
    </section>

    <section id="produk" class="py-5">
        <div class="container text-center">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <h2 class="fw-semibold mb-3">Produk Terbaru</h2>
                    <p class="text-muted">Temukan berbagai jenis produk sablon dan apparel dari Toko Delapan yang
                        bisa kamu pesan sekarang juga!</p>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                @forelse ($produk as $p)
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                            <div class="overflow-hidden">
                                <img src="{{ asset('storage/' . ($p->mockup->first()->file_path ?? 'https://placehold.co/400x300')) }}"
                                    class="card-img-top img-fluid product-img" alt="{{ $p->nama }}">
                            </div>

                            <div class="card-body d-flex flex-column text-center">
                                <h6 class="fw-semibold mb-1">{{ $p->nama }}</h6>
                                <p class="text-muted small mb-2">{{ Str::limit($p->jenis_produk, 60) }}</p>
                                <p class="fw-bold text-primary mb-3">Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </p>

                                <!-- Tombol -->
                                <a href="{{ route('users.produk.show', $p->id) }}"
                                    class="btn btn-primary rounded-pill btn-sm mt-auto">
                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">Belum ada produk tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="kontak" class="py-5">
        <div class="container text-center">
            <h2 class="fw-semibold mb-3">Hubungi Kami</h2>
            <p class="text-muted">📞 +62 812-3456-7890 | 📧 toko8@gmail.com</p>
            <p>📍 Jl. Kauman No 32 A, Ngupasan , Gondomanan, Yogyakarta</p>
        </div>
    </section>

    <script>
        // Animasi fade-in saat scroll
        const fadeElems = document.querySelectorAll('.fade-in');
        const fadeInOnScroll = () => {
            fadeElems.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight - 100) {
                    el.classList.add('show');
                }
            });
        };
        window.addEventListener('scroll', fadeInOnScroll);
        window.addEventListener('load', fadeInOnScroll);
    </script>
@endsection
