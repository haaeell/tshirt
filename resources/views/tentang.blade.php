@extends('layouts.homepage')
@section('title', 'Tentang Kami - Toko Delapan')

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3">Tentang Toko Delapan</h1>
            <p class="text-muted">Kami hadir untuk membantu kamu mendapatkan produk sablon dan printing terbaik dengan
                pelayanan cepat dan hasil maksimal.</p>
        </div>

        <div class="row align-items-center gy-5">
            <div class="col-md-6">
                <img src="https://placehold.co/600x400?text=Workshop+Kami" class="img-fluid rounded shadow-sm"
                    alt="Workshop Toko Delapan">
            </div>
            <div class="col-md-6">
                <h3 class="fw-semibold mb-3">Mengapa Memilih Kami?</h3>
                <ul class="list-unstyled">
                    <li class="mb-2">✅ Kualitas bahan terbaik</li>
                    <li class="mb-2">✅ Proses cepat & rapi</li>
                    <li class="mb-2">✅ Harga terjangkau</li>
                    <li class="mb-2">✅ Pelayanan ramah</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
