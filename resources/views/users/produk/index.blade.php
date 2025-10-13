@extends('layouts.homepage')

@section('title', 'Produk - Toko Delapan')

@section('content')
    <h2 class="mb-5 text-center fw-bold">Daftar Produk</h2>

    <div class="row g-4">
        @foreach ($produk as $p)
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <!-- Gambar Produk -->
                    <div class="overflow-hidden">
                        <img src="https://placehold.co/400x300"
                             class="card-img-top img-fluid product-img"
                             alt="{{ $p->nama }}">
                    </div>

                    <!-- Isi Card -->
                    <div class="card-body d-flex flex-column text-center">
                        <h6 class="fw-semibold mb-1">{{ $p->nama }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($p->jenis_produk, 60) }}</p>
                        <p class="fw-bold text-primary mb-3">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>

                        <!-- Tombol -->
                        <a href="{{ route('users.produk.show', $p->id) }}"
                           class="btn btn-primary rounded-pill btn-sm mt-auto">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .product-img {
            transition: transform 0.3s ease;
        }
        .card:hover .product-img {
            transform: scale(1.05);
        }
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
    </style>
@endsection
