@extends('layouts.homepage')

@section('title', 'Detail Pesanan - Toko Delapan')

@section('content')
    <div class="container my-5">
        <h2 class="fw-bold mb-4 text-center text-primary animate-fade">
            📄 Detail Pesanan
            <span class="text-secondary">
                {{ $pesanan->kode ?? '#ORD' . str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) }}
            </span>
        </h2>

        {{-- ================= ALERT STATUS ================= --}}
        @php
            $alerts = [
                'pending' => [
                    'class' => 'warning',
                    'icon' => 'fa-clock',
                    'title' => 'Menunggu Pembayaran',
                    'message' => 'Silakan lakukan pembayaran sesuai instruksi di bawah untuk melanjutkan pesanan Anda.',
                ],
                'dibayar' => [
                    'class' => 'info',
                    'icon' => 'fa-hourglass-half',
                    'title' => 'Pembayaran Diterima',
                    'message' => 'Pesanan Anda sedang menunggu konfirmasi dari admin. Mohon tunggu beberapa saat.',
                ],
                'diproses' => [
                    'class' => 'primary',
                    'icon' => 'fa-cogs',
                    'title' => 'Pesanan Sedang Diproses',
                    'message' => 'Pesanan Anda sedang disiapkan. Kami akan memberi tahu jika pesanan sudah dikirim.',
                ],
                'dikirim' => [
                    'class' => 'secondary',
                    'icon' => 'fa-truck',
                    'title' => 'Pesanan Dikirim',
                    'message' => 'Pesanan Anda sedang dalam perjalanan menuju alamat tujuan.',
                ],
                'selesai' => [
                    'class' => 'success',
                    'icon' => 'fa-check-circle',
                    'title' => 'Pesanan Selesai',
                    'message' =>
                        'Terima kasih telah berbelanja di Toko Delapan! Kami harap Anda puas dengan produk kami.',
                ],
                'batal' => [
                    'class' => 'danger',
                    'icon' => 'fa-times-circle',
                    'title' => 'Pesanan Dibatalkan',
                    'message' => 'Pesanan ini telah dibatalkan. Jika ini kesalahan, silakan hubungi admin kami.',
                ],
            ];
            $alert = $alerts[$pesanan->status] ?? null;
        @endphp

        @if ($alert)
            <div
                class="alert alert-{{ $alert['class'] }} d-flex align-items-start rounded-4 shadow-sm p-4 mb-4 animate-fade">
                <i class="fas {{ $alert['icon'] }} fs-4 me-3"></i>
                <div>
                    <strong class="d-block mb-1">{{ $alert['title'] }}</strong>
                    <span class="small">{{ $alert['message'] }}</span>
                    @if ($pesanan->status === 'dikirim' && $pesanan->no_resi)
                        <div class="mt-2">
                            <span class="fw-semibold">Nomor Resi:</span>
                            <span class="badge bg-light text-dark ms-1">{{ $pesanan->no_resi }}</span>
                            <a href="https://cekresi.com/?noresi={{ urlencode($pesanan->no_resi) }}" target="_blank"
                                class="btn btn-outline-primary btn-sm rounded-pill px-3 ms-2">
                                <i class="fas fa-search me-1"></i> Lacak Pengiriman
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ================= ALAMAT & RINGKASAN ================= --}}
        <div class="row g-4">
            <!-- ALAMAT PENGIRIMAN -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 animate-fade">
                    <div class="card-header bg-light fw-semibold rounded-top-4">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i> Alamat Pengiriman
                    </div>
                    <div class="card-body small">
                        @if ($pesanan->alamatPengiriman)
                            <dl class="row mb-0">
                                <dt class="col-5 text-muted">Nama</dt>
                                <dd class="col-7 fw-semibold">: {{ $pesanan->alamatPengiriman->nama_penerima }}</dd>

                                <dt class="col-5 text-muted">Telepon</dt>
                                <dd class="col-7">: {{ $pesanan->alamatPengiriman->telepon }}</dd>

                                <dt class="col-5 text-muted">Alamat</dt>
                                <dd class="col-7">
                                    : {{ $pesanan->alamatPengiriman->alamat }},
                                    {{ $pesanan->alamatPengiriman->kota }},
                                    {{ $pesanan->alamatPengiriman->provinsi }}
                                    - {{ $pesanan->alamatPengiriman->kode_pos }}
                                </dd>
                            </dl>
                        @else
                            <p class="text-muted fst-italic mb-0">Alamat pengiriman belum tersedia</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RINGKASAN PESANAN -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 animate-fade">
                    <div class="card-header bg-light fw-semibold rounded-top-4">
                        <i class="fas fa-receipt text-primary me-2"></i> Ringkasan Pesanan
                    </div>
                    <div class="card-body small">
                        @php
                            $statusClass = match ($pesanan->status) {
                                'pending' => 'bg-warning text-dark',
                                'dibayar' => 'bg-primary',
                                'diproses' => 'bg-info text-dark',
                                'dikirim' => 'bg-secondary',
                                'selesai' => 'bg-success',
                                'batal' => 'bg-danger',
                                default => 'bg-light text-dark',
                            };
                        @endphp

                        <dl class="row mb-0">
                            <dt class="col-5 text-muted">Status</dt>
                            <dd class="col-7">:
                                <span class="badge {{ $statusClass }} px-3">{{ ucfirst($pesanan->status) }}</span>
                            </dd>

                            <dt class="col-5 text-muted">Total</dt>
                            <dd class="col-7 fw-semibold">: Rp {{ number_format($pesanan->total, 0, ',', '.') }}</dd>

                            @if ($pesanan->diskon > 0)
                                <dt class="col-5 text-muted">Diskon</dt>
                                <dd class="col-7 text-success">: - Rp
                                    {{ number_format($pesanan->diskon, 0, ',', '.') }}</dd>
                            @endif

                            <dt class="col-5 text-muted">Tanggal Pesanan</dt>
                            <dd class="col-7">: {{ $pesanan->created_at->translatedFormat('d M Y, H:i') }}</dd>
                        </dl>

                        <hr>
                        <div class="text-end">
                            <h5 class="fw-bold text-primary mb-0">
                                Total Bayar: Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= TIMELINE STATUS ================= --}}
        <div class="card border-0 shadow-sm rounded-4 mt-4 animate-fade">
            <div class="card-header bg-light fw-semibold rounded-top-4">
                <i class="fas fa-stream text-primary me-2"></i> Status Pesanan
            </div>
            <div class="card-body py-4">
                @php
                    $statuses = [
                        'pending' => 'Menunggu Pembayaran',
                        'dibayar' => 'Menunggu Konfirmasi Admin',
                        'diproses' => 'Pesanan Diproses',
                        'dikirim' => 'Sedang Dikirim',
                        'selesai' => 'Selesai',
                        'batal' => 'Dibatalkan',
                    ];
                    $icons = [
                        'pending' => 'fa-clock',
                        'dibayar' => 'fa-hourglass-half',
                        'diproses' => 'fa-cogs',
                        'dikirim' => 'fa-truck',
                        'selesai' => 'fa-check-circle',
                        'batal' => 'fa-times-circle',
                    ];
                    $orderStatus = $pesanan->status;
                    $statusKeys = array_keys($statuses);
                    $currentIndex = array_search($orderStatus, $statusKeys);
                @endphp

                <div class="timeline-container d-flex justify-content-between align-items-center flex-wrap">
                    @foreach ($statuses as $key => $label)
                        @php
                            $index = array_search($key, $statusKeys);
                            $isActive = $index <= $currentIndex && $orderStatus !== 'batal';
                            $isCurrent = $index === $currentIndex && $orderStatus !== 'batal';
                            $isCanceled = $orderStatus === 'batal';
                        @endphp

                        <div class="timeline-step text-center flex-fill position-relative">
                            <div
                                class="icon-circle mx-auto mb-2
                                    {{ $isCanceled
                                        ? 'bg-danger text-white'
                                        : ($isActive
                                            ? 'bg-success text-white'
                                            : ($isCurrent
                                                ? 'bg-primary text-white'
                                                : 'bg-light text-muted')) }}">
                                <i class="fas {{ $icons[$key] }}"></i>
                            </div>
                            <small
                                class="fw-semibold d-block
                                    {{ $isCanceled ? 'text-danger' : ($isActive || $isCurrent ? 'text-dark' : 'text-muted') }}">
                                {{ $label }}
                            </small>
                        </div>

                        @if (!$loop->last)
                            <div class="timeline-line flex-fill position-relative">
                                <div
                                    class="line-segment
                                        {{ $isCanceled ? 'bg-danger' : ($index < $currentIndex ? 'bg-success' : 'bg-light') }}">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ==================== AKSI SELESAIKAN PESANAN / REVIEW ==================== --}}
        @if ($pesanan->status === 'dikirim')
            <div class="card border-0 shadow-sm rounded-4 mt-4 animate-fade">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-3">
                        Pesanan Anda sudah diterima?
                    </h6>
                    <form action="{{ route('users.orders.updateStatus', $pesanan->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                            <i class="fas fa-check-circle me-1"></i> Tandai Sebagai Selesai
                        </button>
                    </form>
                    <p class="small text-muted mt-2 mb-0">
                        Dengan menekan tombol ini, pesanan akan dianggap selesai.
                    </p>
                </div>
            </div>
        @endif

        @if ($pesanan->status === 'selesai')
            <div class="card border-0 shadow-sm rounded-4 mt-4 animate-fade">
                <div class="card-header bg-light fw-semibold rounded-top-4">
                    <i class="fas fa-star text-warning me-2"></i> Beri Ulasan Produk
                </div>
                <div class="card-body">
                    @foreach ($pesanan->items as $item)
                        @foreach ($item->details as $detail)
                            @php
                                $produk = $item->produk;
                                $existingReview = $produk
                                    ->ulasan()
                                    ->where('user_id', auth()->id())
                                    ->where('pesanan_item_id', $item->id)
                                    ->first();
                            @endphp

                            <div class="border rounded-4 p-3 mb-4 shadow-sm">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('storage/' . ($produk->image ?? 'default.jpg')) }}"
                                        class="rounded me-3" style="width:60px;height:60px;object-fit:cover;">
                                    <div>
                                        <h6 class="fw-semibold mb-0">{{ $produk->nama }}</h6>
                                        <small class="text-muted">Qty: {{ $detail->qty }}</small>
                                    </div>
                                </div>

                                @if ($existingReview)
                                    <div class="bg-light p-3 rounded-3">
                                        <div class="text-warning mb-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $existingReview->rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-0">{{ $existingReview->komentar ?? 'Tidak ada komentar.' }}</p>
                                        <small class="text-muted">Ulasan Anda •
                                            {{ $existingReview->created_at->diffForHumans() }}</small>
                                    </div>
                                @else
                                    <form action="{{ route('users.orders.reviewProduk', $item->id) }}" method="POST"
                                        class="mt-2">
                                        @csrf
                                        <div class="mb-2">
                                            <div class="star-rating fs-4 text-warning" data-item="{{ $item->id }}">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <input type="radio" class="d-none" name="rating[{{ $item->id }}]"
                                                        id="rating-{{ $item->id }}-{{ $i }}"
                                                        value="{{ $i }}">
                                                    <label for="rating-{{ $item->id }}-{{ $i }}"
                                                        class="me-1" style="cursor:pointer;">★</label>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <textarea name="komentar" rows="2" class="form-control form-control-sm" placeholder="Tulis ulasan Anda..."></textarea>
                                        </div>
                                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                                            <i class="fas fa-paper-plane me-1"></i> Kirim Ulasan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endif


    </div>

    <!-- ==================== PRODUK DIPESAN ==================== -->
    <div class="card border-0 shadow-sm rounded-4 mt-4 animate-fade">
        <div class="card-header bg-light fw-semibold rounded-top-4">
            <i class="fas fa-shopping-bag text-primary me-2"></i> Produk Dipesan
        </div>
        <div class="card-body p-3">
            <div class="table-responsive mt-4">
                <table class="table table-borderless align-middle">
                    <thead class="table-light text-secondary small">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Bahan</th>
                            <th class="text-center">Warna</th>
                            <th class="text-center" style="width: 45%">Varian</th>
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

                                // Ambil rincian tambahan dari item (jika ada)
                                $item = $pesanan->items->firstWhere('produk_id', $g['produk']->id);
                                $tambahan = is_string($item->rincian_tambahan)
                                    ? json_decode($item->rincian_tambahan, true)
                                    : $item->rincian_tambahan;

                                $totalTambahan =
                                    ($tambahan['bahan']['total'] ?? 0) +
                                    collect($tambahan['sablon'] ?? [])->sum('cost');
                            @endphp

                            <tr class="border-bottom align-middle">
                                <!-- Produk -->
                                <td>
                                    @if ($item->custom_sablon_url)
                                        <div class="mt-2">
                                            <span class="badge bg-secondary mb-1">Desain Custom</span><br>
                                            <img src="{{ asset($item->custom_sablon_url) }}" alt="Custom sablon"
                                                class="rounded border"
                                                style="width: 90px; height: 90px; object-fit: contain;">
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . optional($g['produk']->mockup->first())->file_path ?? 'placeholder.png') }}"
                                                class="rounded me-3 shadow-sm border"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <div class="fw-semibold">{{ $g['produk']->nama }}</div>
                                                <small
                                                    class="text-muted">{{ ucfirst($g['produk']->jenis_produk ?? '-') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <!-- Bahan -->
                                <td class="text-center">
                                    <span class="badge bg-info bg-opacity-10 text-dark px-3 py-2 rounded-pill">
                                        {{ $g['bahan'] }}
                                    </span>
                                </td>

                                <!-- Warna -->
                                <td class="text-center">
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill border">
                                        {{ $g['warna'] }}
                                    </span>
                                </td>

                                <!-- Varian -->
                                <td>
                                    <div class="table-responsive rounded-3 border bg-light-subtle">
                                        <table class="table table-sm mb-0 text-center align-middle small">
                                            <thead class="bg-white fw-semibold">
                                                <tr class="text-muted">
                                                    <th style="width: 15%">Lengan</th>
                                                    <th style="width: 15%">Ukuran</th>
                                                    <th style="width: 15%">Qty</th>
                                                    <th style="width: 25%">Harga</th>
                                                    <th style="width: 30%">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($g['details'] as $v)
                                                    <tr>
                                                        <td>{{ ucfirst($v['lengan']) }}</td>
                                                        <td>{{ strtoupper($v['ukuran']) }}</td>
                                                        <td>{{ $v['qty'] }}</td>
                                                        <td>Rp {{ number_format($v['harga_satuan'], 0, ',', '.') }}</td>
                                                        <td class="fw-semibold text-primary">
                                                            Rp {{ number_format($v['subtotal'], 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- 🔹 Tambahan biaya (bahan + sablon) --}}
                                    @if ($tambahan)
                                        <div class="mt-2 small text-muted">
                                            <strong>🧾 Rincian Tambahan:</strong>
                                            <ul class="mb-0 ps-3">
                                                @if (!empty($tambahan['bahan']['nama']))
                                                    <li>
                                                        Bahan: {{ $tambahan['bahan']['nama'] }}
                                                        (+Rp
                                                        {{ number_format($tambahan['bahan']['total'] ?? 0, 0, ',', '.') }})
                                                    </li>
                                                @endif
                                                @if (!empty($tambahan['sablon']))
                                                    <li>Sablon:</li>
                                                    <ul class="mb-0 ps-3">
                                                        @foreach ($tambahan['sablon'] as $s)
                                                            <li>
                                                                {{ $s['type'] ?? '-' }} {{ $s['sizeLabel'] ?? '' }}
                                                                (+Rp {{ number_format($s['cost'] ?? 0, 0, ',', '.') }})
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </ul>

                                            <div class="text-success mt-1">
                                                <strong>Total Tambahan:</strong>
                                                +Rp {{ number_format($totalTambahan, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <td class="text-center fw-semibold">{{ $totalQty }}</td>

                                <td class="text-end fw-bold text-primary">
                                    Rp {{ number_format($totalHarga + $totalTambahan, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== PEMBAYARAN ==================== -->
    @if ($pesanan->status === 'pending')
        <div class="card border-0 shadow-sm rounded-4 mt-4 animate-fade">
            <div class="card-header bg-light fw-semibold rounded-top-4">
                <i class="fas fa-credit-card text-primary me-2"></i> Pembayaran
            </div>
            <div class="card-body">

                <!-- INSTRUKSI -->
                <div class="p-4 bg-warning bg-opacity-10 border-start border-4 border-warning rounded-3 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-info-circle text-warning me-2 fs-5"></i>
                        <h6 class="fw-semibold mb-0 text-warning">Instruksi Pembayaran</h6>
                    </div>
                    <p class="small text-muted mb-2">Silakan transfer sesuai total tagihan ke salah satu rekening
                        berikut:</p>

                    <div class="d-flex flex-column flex-sm-row gap-3 mt-3">
                        <div class="flex-fill bg-white shadow-sm rounded-3 border p-3 d-flex align-items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg"
                                alt="BCA" class="me-3" style="height: 32px;">
                            <div>
                                <strong>Bank BCA</strong><br>
                                <span class="fw-semibold">1234567890</span><br>
                                <small class="text-muted">a.n Toko Delapan</small>
                            </div>
                        </div>
                        <div class="flex-fill bg-white shadow-sm rounded-3 border p-3 d-flex align-items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/1200px-Bank_Mandiri_logo_2016.svg.png"
                                alt="Mandiri" class="me-3" style="height: 28px;">
                            <div>
                                <strong>Bank Mandiri</strong><br>
                                <span class="fw-semibold">9876543210</span><br>
                                <small class="text-muted">a.n Toko Delapan</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 small">
                        <i class="fas fa-exclamation-circle text-warning me-1"></i>
                        Setelah transfer, upload bukti pembayaran di bawah ini.
                    </div>
                </div>

                <!-- SUDAH UPLOAD -->
                @if ($pesanan->bukti_pembayaran)
                    <div class="text-center mb-4">
                        <p class="text-success fw-semibold mb-2">
                            <i class="fas fa-check-circle me-1"></i> Bukti pembayaran sudah diupload
                        </p>
                        <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}"
                            class="img-fluid rounded shadow-sm border" style="max-width: 360px;">
                    </div>
                @endif

                <!-- UPLOAD AREA -->
                <form action="{{ route('users.orders.uploadBukti', $pesanan->id) }}" method="POST"
                    enctype="multipart/form-data" id="formUpload">
                    @csrf
                    <div class="upload-area border-2 border-dashed rounded-4 p-4 text-center mb-3 bg-light"
                        ondragover="this.classList.add('drag')" ondragleave="this.classList.remove('drag')">
                        <i class="fas fa-cloud-upload-alt text-primary fa-2x mb-2"></i>
                        <p class="small text-muted mb-2">Tarik & lepaskan file di sini atau klik tombol di bawah</p>
                        <input type="file" name="bukti" id="buktiInput" class="form-control form-control-sm d-none"
                            required>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3"
                            id="btnChooseFile">
                            <i class="fas fa-folder-open me-1"></i> Pilih File
                        </button>
                        <div id="fileName" class="mt-2 text-muted small d-none"></div>
                    </div>

                    <small class="text-muted d-block mb-3 text-center">Format: JPG, PNG, atau PDF (max 2MB)</small>

                    <div class="text-center">
                        <button class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fas fa-upload me-1"></i> Upload Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    </div>

    <style>
        .animate-fade {
            animation: fadeInUp 0.6s ease-in-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .timeline-container {
            overflow-x: auto;
            white-space: nowrap;
        }

        .timeline-step {
            flex: 1;
            min-width: 100px;
            z-index: 2;
        }

        .icon-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .timeline-line {
            flex: 1;
            height: 4px;
            margin: 0 5px;
            position: relative;
        }

        .line-segment {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 2px;
            transform: translateY(-50%);
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .timeline-step small {
                font-size: 0.75rem;
            }
        }

        dl.row dt {
            width: 40%;
            text-align: start;
        }

        dl.row dd {
            margin-bottom: 0.5rem;
            width: 60%;
        }

        table td,
        table th {
            vertical-align: middle !important;
        }

        table tr:hover {
            background-color: #f8faff;
            transition: 0.2s;
        }

        .badge {
            font-size: 0.78rem;
            padding: 6px 8px;
            border-radius: 8px;
        }

        .card-header {
            border-bottom: 1px solid #eee !important;
        }

        .upload-area {
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .upload-area.drag {
            background-color: #e7f1ff;
            border-color: #0d6efd !important;
        }

        .upload-area:hover {
            background-color: #f8faff;
        }

        .upload-area i {
            opacity: 0.9;
        }

        .upload-area small {
            color: #777;
        }

        .star-rating label {
            color: #ddd;
            transition: color 0.2s;
        }

        .star-rating label.active,
        .star-rating label.hover {
            color: #ffc107;
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============ Upload File ===============
            const btnChoose = document.getElementById('btnChooseFile');
            const buktiInput = document.getElementById('buktiInput');

            if (btnChoose && buktiInput) {
                btnChoose.addEventListener('click', () => buktiInput.click());
                buktiInput.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    const fileName = document.getElementById('fileName');
                    if (file && fileName) {
                        fileName.textContent = "📄 " + file.name;
                        fileName.classList.remove('d-none');
                    }
                });
            }

            // ============ Star Rating ===============
            const containers = document.querySelectorAll('.star-rating');

            containers.forEach((container, cIndex) => {
                const stars = container.querySelectorAll('label');
                const inputs = container.querySelectorAll('input[type="radio"]');

                stars.forEach((star, index) => {
                    star.addEventListener('click', () => {
                        stars.forEach(s => s.classList.remove('active'));
                        for (let i = 0; i <= index; i++) stars[i].classList.add('active');
                        if (inputs[index]) inputs[index].checked = true;
                    });

                    star.addEventListener('mouseenter', () => {
                        stars.forEach(s => s.classList.remove('hover'));
                        for (let i = 0; i <= index; i++) stars[i].classList.add('hover');
                    });

                    star.addEventListener('mouseleave', () => {
                        stars.forEach(s => s.classList.remove('hover'));
                    });
                });
            });
        });
    </script>

@endsection
