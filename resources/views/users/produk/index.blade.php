@extends('layouts.homepage')

@section('title', 'Produk - Toko Delapan')

@section('content')
    <h2 class="mb-4 text-center">Daftar Produk</h2>
    <div class="row g-4">
        @foreach ($produk as $p)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <img src="https://placehold.co/300x200" class="card-img-top" alt="{{ $p->nama }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $p->nama }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($p->deskripsi, 60) }}</p>
                        <p class="fw-bold text-primary mb-3">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                        <a href="{{ route('users.produk.show', $p->id) }}" class="btn btn-outline-primary mt-auto">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
