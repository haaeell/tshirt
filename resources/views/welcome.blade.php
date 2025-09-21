@extends('layouts.homepage')

@section('content')

    <!-- Hero Section -->
    <section class="py-5 bg-light text-center">
        <div class="container">
            <h1 class="fw-bold">TOKO DELAPAN</h1>
            <p class="lead">Layanan sablon kaos, printing, dan produk kreatif lainnya. Cepat, murah, dan berkualitas!
            </p>
            <a href="#" class="btn btn-primary">Booking Sekarang</a>
        </div>
    </section>

    <!-- Profil Kami -->
    <section class="py-5">
        <div class="container text-center">
            <div class="row d-flex justify-content-center">
                <div class="col-8">
                   <div class="mb-5">
                     <h2 class="mb-3">Profil Kami</h2>
                    <p class="mb-4">Kami adalah penyedia layanan sablon profesional dengan berbagai pilihan produk.
                        Temukan layanan terbaik untuk kebutuhan Anda. Kami adalah penyedia layanan sablon profesional
                        dengan berbagai pilihan produk</p>
                   </div>
                    <ul class="nav nav-pills justify-content-center mb-4">
                        <li class="nav-item"><a class="nav-link active" href="#">Semua</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Promo</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">E-Sport</a></li>
                    </ul>
                </div>
            </div>

            <!-- Produk Cards -->
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <img src="https://placehold.co/600x400" class="card-img-top" alt="Produk">
                        <div class="card-body">
                            <h5 class="card-title">Produk 1</h5>
                            <p class="card-text">Rp 50.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <img src="https://placehold.co/600x400" class="card-img-top" alt="Produk">
                        <div class="card-body">
                            <h5 class="card-title">Produk 2</h5>
                            <p class="card-text">Rp 75.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <img src="https://placehold.co/600x400" class="card-img-top" alt="Produk">
                        <div class="card-body">
                            <h5 class="card-title">Produk 3</h5>
                            <p class="card-text">Rp 100.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <img src="https://placehold.co/600x400" class="card-img-top" alt="Produk">
                        <div class="card-body">
                            <h5 class="card-title">Produk 4</h5>
                            <p class="card-text">Rp 120.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
