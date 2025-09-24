@extends('layouts.homepage')

@section('title', 'Detail Pesanan - Toko Delapan')

@section('content')
    <div class="container my-5">
        <h2 class="fw-bold mb-4 text-center">
            📄 Detail Pesanan <span class="text-primary">#{{ $pesanan->kode }}</span>
        </h2>

        <div class="row g-4">
            <!-- Alamat Pengiriman -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light fw-semibold">📍 Alamat Pengiriman</div>
                    <div class="card-body small">
                        <p><strong>Nama:</strong> {{ $pesanan->nama_penerima }}</p>
                        <p><strong>Telepon:</strong> {{ $pesanan->telepon }}</p>
                        <p><strong>Alamat:</strong><br>
                            {{ $pesanan->alamat }}, {{ $pesanan->kota }}, {{ $pesanan->provinsi }} -
                            {{ $pesanan->kode_pos }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light fw-semibold">🧾 Ringkasan Pesanan</div>
                    <div class="card-body small">
                        <p><strong>Status:</strong>
                            <span
                                class="badge
                            @if ($pesanan->status === 'selesai') bg-success
                            @elseif($pesanan->status === 'batal') bg-danger
                            @elseif($pesanan->status === 'diproses') bg-info
                            @elseif($pesanan->status === 'menunggu_pembayaran') bg-warning text-dark
                            @else bg-secondary @endif">
                                {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                            </span>
                        </p>
                        <p><strong>Subtotal:</strong> Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}</p>
                        <p><strong>Ongkir:</strong> Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</p>
                        @if ($pesanan->diskon > 0)
                            <p class="text-success"><strong>Diskon Voucher:</strong> - Rp
                                {{ number_format($pesanan->diskon, 0, ',', '.') }}</p>
                        @endif
                        <hr>
                        <h5 class="fw-bold text-primary mb-0">Total: Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-light fw-semibold">🛍️ Produk Dipesan</div>
            <div class="card-body p-3"> {{-- kasih padding biar lega --}}
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
                                <tr>
                                    <td class="fw-semibold">{{ $item->nama_produk }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1"> {{-- kasih jarak antar badge --}}
                                            <span class="badge bg-secondary">{{ $item->warna ?? '-' }}</span>
                                            <span class="badge bg-light text-dark">Ukuran:
                                                {{ strtoupper($item->ukuran ?? '-') }}</span>
                                            <span class="badge bg-light text-dark">Lengan:
                                                {{ ucfirst($item->lengan ?? '-') }}</span>
                                            @if ($item->bahan)
                                                <span class="badge bg-light text-dark">Bahan: {{ $item->bahan }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Pembayaran -->
        @if ($pesanan->status == 'menunggu_pembayaran')
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light fw-semibold">💳 Pembayaran</div>
                <div class="card-body">
                    <div class="alert alert-warning small">
                        Silakan transfer ke rekening berikut sebelum pesanan diproses:
                        <ul class="mb-0 mt-2">
                            <li><strong>Bank BCA</strong> – 1234567890 a.n <strong>Toko Delapan</strong></li>
                            <li><strong>Bank Mandiri</strong> – 9876543210 a.n <strong>Toko Delapan</strong></li>
                        </ul>
                        <p class="mt-2 mb-0">Setelah transfer, upload bukti pembayaran di bawah ini.</p>
                    </div>

                    @if ($pesanan->pembayaran && $pesanan->pembayaran->bukti)
                        <p class="text-success fw-semibold">Bukti pembayaran sudah diupload:</p>
                        <img src="{{ asset('storage/' . $pesanan->pembayaran->bukti) }}"
                            class="img-fluid rounded shadow-sm mb-3" style="max-width: 300px;">
                    @endif

                    <form action="{{ route('users.orders.uploadBukti', $pesanan->id) }}" method="POST"
                        enctype="multipart/form-data">
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
@endsection
