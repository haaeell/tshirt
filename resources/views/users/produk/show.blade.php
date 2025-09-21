@extends('layouts.homepage')

@section('title', $produk->nama . ' - Toko Delapan')

@section('content')
    <div class="row">
        <!-- Gambar produk -->
        <div class="col-md-5">
            <img src="https://placehold.co/500x400" class="img-fluid rounded shadow-sm" alt="{{ $produk->nama }}">
        </div>

        <!-- Info produk -->
        <div class="col-md-7">
            <h2>{{ $produk->nama }}</h2>
            <p class="text-muted">{{ $produk->deskripsi }}</p>
            <h4 class="text-primary mb-4">Rp {{ number_format($produk->harga, 0, ',', '.') }}</h4>

            <!-- Form tambah ke keranjang -->
            <form action="{{ route('users.cart.store') }}" method="POST">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                <!-- Pilih Varian -->
                @if ($produk->varian->count())
                    <div class="mb-3">
                        <label class="form-label">Pilih Varian</label>
                        <select name="produk_varian_id" class="form-select" required>
                            <option value="">-- Pilih Varian --</option>
                            @foreach ($produk->varian as $v)
                                <option value="{{ $v->id }}">
                                    {{ $v->warna }} | Ukuran: {{ $v->ukuran }} | Lengan: {{ $v->lengan }} |
                                    Rp {{ number_format($v->harga ?? $produk->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Qty -->
                <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="qty" value="1" min="1" class="form-control"
                        style="width:120px;">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                </button>
            </form>
        </div>
    </div>
@endsection
