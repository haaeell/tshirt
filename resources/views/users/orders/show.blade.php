@extends('layouts.homepage')

@section('title', 'Detail Pesanan - Toko Delapan')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4 text-center">
        📄 Detail Pesanan <span class="text-primary">
            {{ $pesanan->kode ?? '#ORD' . str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) }}
        </span>
    </h2>

    <div class="row g-4">
        <!-- ==================== ALAMAT PENGIRIMAN ==================== -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">📍 Alamat Pengiriman</div>
                <div class="card-body small">
                    @if($pesanan->alamatPengiriman)
                        <p><strong>Nama:</strong> {{ $pesanan->alamatPengiriman->nama_penerima }}</p>
                        <p><strong>Telepon:</strong> {{ $pesanan->alamatPengiriman->telepon }}</p>
                        <p><strong>Alamat:</strong><br>
                            {{ $pesanan->alamatPengiriman->alamat }}, {{ $pesanan->alamatPengiriman->kota }},
                            {{ $pesanan->alamatPengiriman->provinsi }} - {{ $pesanan->alamatPengiriman->kode_pos }}
                        </p>
                    @else
                        <p class="text-muted fst-italic">Alamat pengiriman belum tersedia</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- ==================== RINGKASAN PESANAN ==================== -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">🧾 Ringkasan Pesanan</div>
                <div class="card-body small">
                    @php
                        $statusClass = match($pesanan->status) {
                            'pending' => 'bg-warning text-dark',
                            'dibayar' => 'bg-primary',
                            'diproses' => 'bg-info text-dark',
                            'dikirim' => 'bg-secondary',
                            'selesai' => 'bg-success',
                            'batal' => 'bg-danger',
                            default => 'bg-light text-dark'
                        };
                    @endphp

                    <p><strong>Status:</strong>
                        <span class="badge {{ $statusClass }}">{{ ucfirst($pesanan->status) }}</span>
                    </p>
                    <p><strong>Total:</strong> Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                    @if ($pesanan->diskon > 0)
                        <p class="text-success"><strong>Diskon:</strong> - Rp {{ number_format($pesanan->diskon, 0, ',', '.') }}</p>
                    @endif
                    <p><strong>Tanggal Pesanan:</strong> {{ $pesanan->created_at->translatedFormat('d M Y, H:i') }}</p>
                    <hr>
                    <h5 class="fw-bold text-primary mb-0">
                        Total Bayar: Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== PRODUK DIPESAN ==================== -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-light fw-semibold">🛍️ Produk Dipesan</div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Varian</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pesanan->items as $item)
                            @foreach ($item->details as $detail)
                                <tr>
                                    <td class="fw-semibold">{{ $item->produk->nama ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @if($item->warna)
                                                <span class="badge bg-light text-dark">Warna: {{ $item->warna }}</span>
                                            @endif
                                            @if($item->bahan)
                                                <span class="badge bg-light text-dark">Bahan: {{ $item->bahan }}</span>
                                            @endif
                                            @if($item->lengan)
                                                <span class="badge bg-light text-dark">Lengan: {{ ucfirst($item->lengan) }}</span>
                                            @endif
                                            <span class="badge bg-light text-dark">Ukuran: {{ strtoupper($detail->ukuran) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $detail->qty }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== PEMBAYARAN ==================== -->
    @if ($pesanan->status === 'pending')
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-light fw-semibold">💳 Pembayaran</div>
            <div class="card-body">
                <div class="alert alert-warning small">
                    Silakan transfer ke rekening berikut:
                    <ul class="mb-0 mt-2">
                        <li><strong>Bank BCA</strong> – 1234567890 a.n <strong>Toko Delapan</strong></li>
                        <li><strong>Bank Mandiri</strong> – 9876543210 a.n <strong>Toko Delapan</strong></li>
                    </ul>
                    <p class="mt-2 mb-0">Setelah transfer, upload bukti pembayaran di bawah ini.</p>
                </div>

                @if ($pesanan->bukti_pembayaran)
                    <p class="text-success fw-semibold">Bukti pembayaran sudah diupload:</p>
                    <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}"
                         class="img-fluid rounded shadow-sm mb-3" style="max-width: 300px;">
                @endif

                <form action="{{ route('users.orders.uploadBukti', $pesanan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="bukti" class="form-control form-control-sm" required>
                        <small class="text-muted">Format: JPG, PNG, PDF (max 2MB)</small>
                    </div>
                    <button class="btn btn-success rounded-pill px-4">
                        <i class="fas fa-upload me-1"></i> Upload Bukti
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>

<style>
.badge { font-size: 0.8rem; padding: 6px 8px; border-radius: 6px; }
</style>
@endsection
