@extends('layouts.homepage')

@section('title', 'Detail Pesanan - Toko Delapan')

@section('content')
    <h2 class="mb-4 text-center">Detail Pesanan #{{ $pesanan->kode }}</h2>

    <div class="row">
        <!-- Info Pengiriman -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header">Alamat Pengiriman</div>
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ $pesanan->nama_penerima }}</p>
                    <p><strong>Telepon:</strong> {{ $pesanan->telepon }}</p>
                    <p><strong>Alamat:</strong> {{ $pesanan->alamat }}, {{ $pesanan->kota }},
                        {{ $pesanan->provinsi }} - {{ $pesanan->kode_pos }}</p>
                </div>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header">Ringkasan Pesanan</div>
                <div class="card-body">
                    <p><strong>Status:</strong>
                        <span
                            class="badge bg-{{ $pesanan->status == 'selesai' ? 'success' : ($pesanan->status == 'batal' ? 'danger' : 'warning') }}">
                            {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                        </span>
                    </p>
                    <p><strong>Subtotal:</strong> Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}</p>
                    <p><strong>Ongkir:</strong> Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</p>
                    <hr>
                    <h5>Total: Rp {{ number_format($pesanan->total, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Items -->
    <div class="card shadow-sm mt-3">
        <div class="card-header">Produk</div>
        <div class="card-body">
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Varian</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanan->items as $item)
                        <tr>
                            <td>{{ $item->nama_produk }}</td>
                            <td>
                                {{ $item->warna ?? '-' }},
                                {{ $item->ukuran ?? '-' }},
                                {{ $item->lengan ?? '-' }}
                            </td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upload Bukti (kalau status menunggu pembayaran) -->
    @if ($pesanan->status == 'menunggu_pembayaran')
        <div class="card shadow-sm mt-3">
            <div class="card-header">Upload Bukti Pembayaran</div>
            <div class="card-body">
                @if ($pesanan->pembayaran && $pesanan->pembayaran->bukti)
                    <p>Bukti sudah diupload:</p>
                    <img src="{{ asset('storage/' . $pesanan->pembayaran->bukti) }}" class="img-fluid mb-3"
                        style="max-width: 300px;">
                @endif

                <form action="{{ route('users.orders.uploadBukti', $pesanan->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="bukti" class="form-control" required>
                    </div>
                    <button class="btn btn-success">
                        <i class="fas fa-upload"></i> Upload Bukti
                    </button>
                </form>
            </div>
        </div>
    @endif

@endsection
