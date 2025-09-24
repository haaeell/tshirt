@extends('layouts.homepage')

@section('content')
    <!-- Hero Section -->
    <section class="py-5 text-white text-center d-flex align-items-center"
        style="min-height: 60vh; border-radius:16px;
                background: linear-gradient(135deg, #0d47a1 0%, #000428 100%);">
        <div class="container">
            <h1 class="fw-bold display-4 mb-3">TOKO DELAPAN</h1>
            <p class="lead mb-4">Layanan sablon kaos, printing, dan produk kreatif lainnya. Cepat, murah, dan berkualitas!
            </p>
            <a href="#" class="btn btn-light btn-lg px-4 rounded-pill shadow-sm">Booking Sekarang</a>
        </div>
    </section>


    <!-- Profil Kami -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-3">Profil Kami</h2>
                    <p class="text-muted">
                        Toko Delapan adalah penyedia layanan sablon dan printing profesional yang berfokus pada kualitas
                        hasil
                        dan kepuasan pelanggan. Dengan pengalaman bertahun-tahun di bidang percetakan, kami menghadirkan
                        beragam pilihan produk mulai dari sablon kaos, jersey, merchandise komunitas, hingga kebutuhan
                        promosi bisnis Anda.
                    </p>
                    <p class="text-muted">
                        Kami percaya bahwa setiap karya memiliki cerita, dan melalui layanan kami, cerita tersebut bisa
                        diwujudkan dalam bentuk desain yang menarik dan produk yang tahan lama. Proses produksi dilakukan
                        dengan teknologi modern, tinta berkualitas, serta tim berpengalaman yang memastikan hasil terbaik
                        untuk setiap pesanan.
                    </p>
                </div>
            </div>

            <!-- Produk Cards -->
            <div class="row g-4">
                @foreach ([['nama' => 'Produk 1', 'harga' => '50.000'], ['nama' => 'Produk 2', 'harga' => '75.000'], ['nama' => 'Produk 3', 'harga' => '100.000'], ['nama' => 'Produk 4', 'harga' => '120.000']] as $produk)
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100 border-0 shadow-sm rounded-3">
                            <img src="https://placehold.co/600x400" class="card-img-top rounded-top"
                                alt="{{ $produk['nama'] }}">
                            <div class="card-body text-center">
                                <h6 class="fw-bold mb-1">{{ $produk['nama'] }}</h6>
                                <p class="text-primary fw-semibold mb-3">Rp {{ $produk['harga'] }}</p>
                                <a href="#" class="btn btn-sm btn-primary rounded-pill px-3">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endsection
