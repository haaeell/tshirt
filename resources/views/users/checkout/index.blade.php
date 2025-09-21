@extends('layouts.homepage')

@section('title', 'Checkout - Toko Delapan')

@section('content')
    <h2 class="mb-4 text-center">Checkout</h2>

    <form action="{{ route('users.placeOrder') }}" method="POST">
        @csrf
        <div class="row">
            <!-- alamat -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-3">
                    <div class="card-header">Alamat Pengiriman</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Penerima</label>
                            <input type="text" name="nama_penerima" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="kota" class="form-control" placeholder="Kota" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="provinsi" class="form-control" placeholder="Provinsi" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="kode_pos" class="form-control" placeholder="Kode Pos" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ringkasan -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Ringkasan Belanja</div>
                    <div class="card-body">
                        <ul class="list-group mb-3">
                            @foreach ($keranjang->items as $item)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $item->produk->nama }} x {{ $item->qty }}</span>
                                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <p class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <strong>Rp {{ number_format($keranjang->items->sum('subtotal'), 0, ',', '.') }}</strong>
                        </p>
                        <p class="d-flex justify-content-between">
                            <span>Ongkir</span>
                            <strong>Rp 20.000</strong>
                        </p>
                        <hr>
                        <p class="d-flex justify-content-between">
                            <span>Total</span>
                            <strong>Rp {{ number_format($keranjang->items->sum('subtotal') + 20000, 0, ',', '.') }}</strong>
                        </p>
                        <button class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-credit-card"></i> Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
