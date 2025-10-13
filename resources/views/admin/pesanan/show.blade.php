@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-file-invoice me-2 text-primary"></i> Detail Pesanan
                <span class="text-muted">#{{ $pesanan->kode ?? str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) }}</span>
            </h4>
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-light border">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- ================= INFO PESANAN ================= -->
        <div class="row g-4 mb-4">
            <!-- Informasi Pesanan -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom fw-semibold">
                        <i class="fas fa-box me-2 text-primary"></i> Informasi Pesanan
                    </div>
                    <div class="card-body small">
                        <dl class="row mb-0">
                            <dt class="col-5 text-muted">Pelanggan</dt>
                            <dd class="col-7 fw-semibold">: {{ $pesanan->user->nama }}</dd>

                            <dt class="col-5 text-muted">Total</dt>
                            <dd class="col-7 fw-semibold text-primary">
                                : Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                            </dd>

                            <dt class="col-5 text-muted">Status</dt>
                            <dd class="col-7">
                                @php
                                    $colors = [
                                        'pending' => 'warning text-dark',
                                        'dibayar' => 'primary',
                                        'diproses' => 'info text-dark',
                                        'dikirim' => 'secondary',
                                        'selesai' => 'success',
                                        'batal' => 'danger',
                                    ];
                                @endphp
                                : <span class="badge bg-{{ $colors[$pesanan->status] ?? 'light text-dark' }}">
                                    {{ ucfirst($pesanan->status) }}
                                </span>
                            </dd>

                            <dt class="col-5 text-muted">Tanggal</dt>
                            <dd class="col-7">: {{ $pesanan->created_at->translatedFormat('d M Y, H:i') }}</dd>
                        </dl>

                        <form method="POST" action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}"
                            class="mt-3">
                            @csrf
                            <div class="input-group input-group-sm">
                                <select name="status" class="form-select">
                                    @foreach (['pending', 'dibayar', 'diproses', 'dikirim', 'selesai', 'batal'] as $s)
                                        <option value="{{ $s }}" {{ $pesanan->status == $s ? 'selected' : '' }}>
                                            {{ ucfirst($s) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-sync-alt me-1"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Alamat Pengiriman -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom fw-semibold">
                        <i class="fas fa-map-marker-alt me-2 text-primary"></i> Alamat Pengiriman
                    </div>
                    <div class="card-body small">
                        @if ($pesanan->alamatPengiriman)
                            <dl class="row mb-0">
                                <dt class="col-5 text-muted">Nama Penerima</dt>
                                <dd class="col-7 fw-semibold"> : {{ $pesanan->alamatPengiriman->nama_penerima }}</dd>

                                <dt class="col-5 text-muted">Alamat</dt>
                                <dd class="col-7"> : {{ $pesanan->alamatPengiriman->alamat }}</dd>

                                <dt class="col-5 text-muted">Kota / Provinsi</dt>
                                <dd class="col-7">
                                    : {{ $pesanan->alamatPengiriman->kota }}, {{ $pesanan->alamatPengiriman->provinsi }}
                                </dd>

                                <dt class="col-5 text-muted">Kode Pos</dt>
                                <dd class="col-7"> : {{ $pesanan->alamatPengiriman->kode_pos ?? '-' }}</dd>

                                <dt class="col-5 text-muted">Telepon</dt>
                                <dd class="col-7"> : {{ $pesanan->alamatPengiriman->telepon }}</dd>
                            </dl>
                        @else
                            <p class="text-muted fst-italic mb-0">Alamat belum diisi</p>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom fw-semibold">
                        <i class="fas fa-credit-card me-2 text-primary"></i> Bukti Pembayaran
                    </div>
                    <div class="card-body small text-center">
                        @if ($pesanan->bukti_pembayaran)
                            <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}"
                                class="img-fluid rounded shadow-sm mb-3" style="max-height: 180px;">

                            @if (in_array($pesanan->status, ['pending', 'dibayar']))
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="{{ route('admin.pesanan.approve', $pesanan->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i> Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.pesanan.reject', $pesanan->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-times me-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            @else
                                <p class="text-success fw-semibold mb-0 mt-2">
                                    <i class="fas fa-check-circle me-1"></i> Pembayaran disetujui
                                </p>
                            @endif
                        @else
                            <p class="text-muted fst-italic">Belum ada bukti pembayaran</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pengiriman -->
            @if (in_array($pesanan->status, ['diproses', 'dikirim']))
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-bottom fw-semibold">
                            <i class="fas fa-truck me-2 text-primary"></i> Pengiriman
                        </div>
                        <div class="card-body small">
                            @if ($pesanan->no_resi)
                                <div class="text-center py-3">
                                    <i class="fas fa-truck text-success mb-2" style="font-size: 48px;"></i>
                                    <h6 class="fw-bold text-success mb-1">Pesanan Telah Dikirim</h6>
                                    <p class="text-muted small mb-2">Nomor Resi:</p>
                                    <p class="fw-semibold fs-6 mb-0">{{ $pesanan->no_resi }}</p>
                                </div>
                            @else
                                <form action="{{ route('admin.pesanan.updateResi', $pesanan->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Nomor Resi</label>
                                        <input type="text" name="no_resi" class="form-control form-control-sm"
                                            placeholder="Masukkan nomor resi" required>
                                    </div>
                                    <button class="btn btn-primary btn-sm w-100 rounded-pill">
                                        <i class="fas fa-paper-plane me-1"></i> Kirim Pesanan
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- ================= PRODUK ================= -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom fw-semibold">
                <i class="fas fa-shopping-bag me-2 text-primary"></i> Produk Dipesan
            </div>
            <div class="card-body p-3">
                <div class="table-responsive mt-3">
                    <table class="table table-borderless align-middle table-hover shadow-sm">
                        <thead class="table-light text-secondary small">
                            <tr>
                                <th>Produk</th>
                                <th>Bahan</th>
                                <th>Warna</th>
                                <th style="width: 45%">Varian</th>
                                <th class="text-center">Total Qty</th>
                                <th class="text-end">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grouped = [];

                                foreach ($pesanan->items as $item) {
                                    $key = $item->produk_id . '|' . $item->bahan . '|' . $item->warna;

                                    if (!isset($grouped[$key])) {
                                        $grouped[$key] = [
                                            'produk' => $item->produk,
                                            'bahan' => $item->bahan,
                                            'warna' => $item->warna,
                                            'details' => [],
                                        ];
                                    }

                                    foreach ($item->details as $d) {
                                        $grouped[$key]['details'][] = [
                                            'lengan' => $d->lengan,
                                            'ukuran' => $d->ukuran,
                                            'qty' => $d->qty,
                                            'harga_satuan' => $d->harga_satuan,
                                            'subtotal' => $d->subtotal,
                                        ];
                                    }
                                }
                            @endphp

                            @foreach ($grouped as $g)
                                @php
                                    $totalQty = collect($g['details'])->sum('qty');
                                    $totalHarga = collect($g['details'])->sum('subtotal');
                                @endphp

                                <tr class="border-bottom">
                                    <!-- Produk -->
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . ($g['produk']->image ?? 'placeholder.png')) }}"
                                                class="rounded me-3 shadow-sm border"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <div class="fw-semibold">{{ $g['produk']->nama }}</div>
                                                <small
                                                    class="text-muted">{{ ucfirst($g['produk']->jenis_produk ?? '-') }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Bahan -->
                                    <td class="text-center align-middle">
                                        <span class="badge bg-info bg-opacity-10 text-dark px-3 py-2 rounded-pill">
                                            {{ $g['bahan'] }}
                                        </span>
                                    </td>

                                    <!-- Warna -->
                                    <td class="text-center align-middle">
                                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                            {{ $g['warna'] }}
                                        </span>
                                    </td>

                                    <!-- Varian -->
                                    <td class="align-middle">
                                        <div class="table-responsive rounded-3 border bg-light-subtle">
                                            <table class="table table-sm mb-0 text-center align-middle small">
                                                <thead class="bg-white fw-semibold">
                                                    <tr class="text-muted">
                                                        <th>Lengan</th>
                                                        <th>Ukuran</th>
                                                        <th>Qty</th>
                                                        <th>Harga</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($g['details'] as $v)
                                                        <tr>
                                                            <td>{{ ucfirst($v['lengan']) }}</td>
                                                            <td>{{ strtoupper($v['ukuran']) }}</td>
                                                            <td>{{ $v['qty'] }}</td>
                                                            <td>Rp {{ number_format($v['harga_satuan'], 0, ',', '.') }}
                                                            </td>
                                                            <td class="fw-semibold text-primary">
                                                                Rp {{ number_format($v['subtotal'], 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>

                                    <!-- Total Qty -->
                                    <td class="text-center fw-semibold align-middle">{{ $totalQty }}</td>

                                    <!-- Total Harga -->
                                    <td class="text-end fw-bold text-primary align-middle">
                                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
