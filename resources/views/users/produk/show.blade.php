@extends('layouts.homepage')

@section('title', $produk->nama . ' - Detail Produk')

@section('content')
    <div class="container py-5">
        <div class="row g-5 align-items-start">
            <!-- ========== MOCKUP / GALERI PRODUK ========== -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="position-relative">
                        <img id="mainImage"
                            src="{{ asset('storage/' . ($produk->mockup->first()->file_path ?? 'placeholder.png')) }}"
                            class="img-fluid rounded w-100" alt="{{ $produk->nama }}">
                    </div>

                    <!-- Thumbnail Carousel -->
                    <div class="d-flex overflow-auto gap-2 p-3 border-top bg-light">
                        @foreach ($produk->mockup as $m)
                            <img src="{{ asset('storage/' . $m->file_path) }}" class="mockup-thumb rounded shadow-sm"
                                style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                alt="mockup {{ $m->angle }}">
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ========== DETAIL PRODUK ========== -->
            <div class="col-md-6">
                <h2 class="fw-bold mb-1">{{ $produk->nama }}</h2>
                <p class="text-muted mb-2">{{ ucfirst($produk->jenis_produk) }}</p>
                <h4 class="text-primary fw-bold mb-4">Rp {{ number_format($produk->harga, 0, ',', '.') }}</h4>

                <!-- WARNA -->
                <div class="mb-3">
                    <label class="fw-semibold mb-2">Warna:</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($produk->warna as $w)
                            <div class="color-option" style="background: {{ $w->hex ?? '#ccc' }}"
                                data-nama="{{ $w->nama }}" title="{{ $w->nama }}"></div>
                        @endforeach
                    </div>
                    <input type="hidden" id="warnaHidden" name="warna">
                </div>

                <!-- BAHAN -->
                <div class="mb-4">
                    <label class="fw-semibold mb-2">Bahan:</label>
                    <select id="bahanSelect" class="form-select w-75">
                        <option value="">Pilih bahan</option>
                        @foreach ($produk->bahan as $b)
                            <option value="{{ $b->nama }}" data-harga="{{ $b->tambahan_harga }}">
                                {{ $b->nama }} (+Rp {{ number_format($b->tambahan_harga, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- JUMLAH PER UKURAN + LENGAN -->
                <div class="mb-4">
                    <label class="fw-semibold mb-2">Jumlah per Ukuran & Lengan:</label>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Ukuran</th>
                                    <th rowspan="2" class="align-middle">Tambahan Harga</th>
                                    <th colspan="2">Qty per Lengan</th>
                                </tr>
                                <tr>
                                    <th>Pendek</th>
                                    <th>Panjang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ukuran as $u)
                                    @php
                                        $hargaPendek =
                                            $produk->lengan->where('tipe', 'pendek')->first()->tambahan_harga ?? 0;
                                        $hargaPanjang =
                                            $produk->lengan->where('tipe', 'panjang')->first()->tambahan_harga ?? 0;
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ strtoupper($u->nama) }}</td>
                                        <td>+Rp {{ number_format($u->tambahan_harga, 0, ',', '.') }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm qty-input mx-auto"
                                                data-ukuran="{{ $u->nama }}" data-lengan="pendek"
                                                data-harga="{{ $u->tambahan_harga + $hargaPendek }}" value="0"
                                                min="0" style="width:80px;">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm qty-input mx-auto"
                                                data-ukuran="{{ $u->nama }}" data-lengan="panjang"
                                                data-harga="{{ $u->tambahan_harga + $hargaPanjang }}" value="0"
                                                min="0" style="width:80px;">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CUSTOM SABLON -->
                <div class="mb-4">
                    <button class="btn btn-outline-dark w-100">
                        <i class="fas fa-paint-brush me-2"></i> Tambah Custom Sablon
                    </button>
                </div>

                <!-- TOTAL -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Total:</h5>
                    <h4 id="totalHarga" class="text-primary fw-bold mb-0">Rp 0</h4>
                </div>

                <!-- TOMBOL -->
                <div class="d-flex gap-3">
                    <form method="POST" action="{{ route('users.cart.store') }}" class="flex-fill">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                        <input type="hidden" name="warna" id="warnaHiddenInput">
                        <input type="hidden" name="bahan" id="bahanHidden">
                        <input type="hidden" name="detail_json" id="detailJson">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                            <i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang
                        </button>
                    </form>

                    <a href="#" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="fas fa-credit-card me-1"></i> Beli Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
    <style>
        .color-option {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #ddd;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .color-option:hover {
            transform: scale(1.1);
        }

        .color-option.active {
            border: 3px solid #0d6efd;
        }

        .mockup-thumb.active {
            border: 3px solid #0d6efd;
            transform: scale(1.05);
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        /* ===== Gambar Produk ===== */
        #mainImage {
            max-width: 100%;
            max-height: 350px;
            object-fit: contain;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .d-flex.overflow-auto img {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .d-flex.overflow-auto img:hover {
            transform: scale(1.05);
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.2);
        }

        /* ===== Tampilan mobile ===== */
        @media (max-width: 768px) {
            #mainImage {
                max-height: 300px;
            }
        }
    </style>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const basePrice = {{ $produk->harga }};
                const colorOptions = document.querySelectorAll('.color-option');
                const mockupThumbs = document.querySelectorAll('.mockup-thumb');
                const mainImage = document.getElementById('mainImage');
                const bahanSelect = document.getElementById('bahanSelect');
                const qtyInputs = document.querySelectorAll('.qty-input');
                const totalHargaEl = document.getElementById('totalHarga');
                const warnaHidden = document.getElementById('warnaHiddenInput');
                const bahanHidden = document.getElementById('bahanHidden');
                const detailJson = document.getElementById('detailJson');

                // Ganti gambar utama saat klik thumbnail
                mockupThumbs.forEach(img => {
                    img.addEventListener('click', () => {
                        mockupThumbs.forEach(t => t.classList.remove('active'));
                        img.classList.add('active');
                        mainImage.src = img.src;
                    });
                });

                // Pilih warna
                colorOptions.forEach(el => {
                    el.addEventListener('click', () => {
                        colorOptions.forEach(c => c.classList.remove('active'));
                        el.classList.add('active');
                        warnaHidden.value = el.dataset.nama;
                    });
                });

                // Update total harga realtime
                function updateTotal() {
                    const bahanHarga = parseFloat(bahanSelect.selectedOptions[0]?.dataset.harga || 0);
                    let total = 0;
                    let detail = [];

                    qtyInputs.forEach(input => {
                        const qty = parseInt(input.value || 0);
                        const hargaTambahan = parseFloat(input.dataset.harga);
                        if (qty > 0) {
                            const harga = basePrice + bahanHarga + hargaTambahan;
                            const subtotal = harga * qty;
                            total += subtotal;
                            detail.push({
                                ukuran: input.dataset.ukuran,
                                lengan: input.dataset.lengan,
                                qty,
                                harga_satuan: harga,
                                subtotal
                            });
                        }
                    });

                    detailJson.value = JSON.stringify(detail);
                    totalHargaEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
                    bahanHidden.value = bahanSelect.value;
                }

                bahanSelect.addEventListener('change', updateTotal);
                qtyInputs.forEach(i => i.addEventListener('input', updateTotal));
            });
        </script>
    @endpush
@endsection
