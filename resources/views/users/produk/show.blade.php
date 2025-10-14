@extends('layouts.homepage')

@section('title', $produk->nama . ' - Detail Produk')

@section('content')
    <div class="container py-4" style="max-width: 1100px;">
        <div class="row g-5 align-items-start">
            <!-- ========== MOCKUP / GALERI PRODUK ========== -->
            <div class="col-lg-5 col-md-6">
                <div class="card border-1 shadow-sm overflow-hidden">
                    <div class="position-relative">
                        <div class="mockup-stage ratio ratio-1x1">
                            <div id="colorLayer" class="mockup-color"></div>
                            <img id="mockupImg"
                                src="{{ asset('storage/' . ($produk->mockup->first()->file_path ?? 'placeholder.png')) }}"
                                alt="{{ $produk->nama }}" class="mockup-img"
                                data-id="{{ $produk->mockup->first()->id ?? 1 }}">
                            <canvas id="sablonCanvas" width="600" height="600"
                                style="position:absolute; inset:0; z-index:5; width:100%; height:100%; cursor:move;"></canvas>
                        </div>
                    </div>

                    <!-- Thumbnail Carousel -->
                    <div class="d-flex overflow-auto gap-2 p-3 border-top bg-light rounded-bottom">
                        @foreach ($produk->mockup as $m)
                            <img src="{{ asset('storage/' . $m->file_path) }}" class="mockup-thumb rounded shadow-sm"
                                style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                alt="mockup {{ $m->angle }}">
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ========== DETAIL PRODUK ========== -->
            <div class="col-lg-7 col-md-6">
                <h4 class="fw-bold mb-1">{{ $produk->nama }}</h4>
                <p class="text-muted m-0">{{ ucfirst($produk->jenis_produk) }}</p>
                <h5 class="text-dark fw-bold mb-3">Rp {{ number_format($produk->harga, 0, ',', '.') }}</h5>

                <div class="row">
                    <div class="col-md-6">
                        <!-- WARNA -->
                        <div class="mb-2">
                            <label class="fw-semibold mb-1 small">Warna:</label>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($produk->warna as $w)
                                    <div class="color-option" style="background: {{ $w->hex ?? '#ccc' }}"
                                        data-nama="{{ $w->nama }}" data-hex="{{ $w->hex ?? '#ccc' }}"
                                        title="{{ $w->nama }}"></div>
                                @endforeach
                            </div>
                            <input type="hidden" id="warnaHiddenInput" name="warna">
                        </div>
                    </div>

                    <div class="col-md-6">


                        <!-- BAHAN -->
                        <div class="mb-3">
                            <label class="fw-semibold mb-1 small">Bahan:</label>
                            <select id="bahanSelect" class="form-select w-100">
                                <option value="">Pilih bahan</option>
                                @foreach ($produk->bahan as $b)
                                    <option value="{{ $b->nama }}" data-harga="{{ $b->tambahan_harga }}">
                                        {{ $b->nama }} (+Rp {{ number_format($b->tambahan_harga, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- CUSTOM SABLON -->
                <div class="mb-3">
                    <div class="d-flex gap-1">
                        <input type="file" id="uploadSablonInput" class="form-control" accept="image/*">
                        <button id="resetCanvas" class="btn btn-outline-secondary flex-shrink-0">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <button id="saveSablonBtn" class="btn btn-dark w-100 shadow-sm">
                    <i class="fas fa-save me-1"></i> Simpan Desain
                </button>
                <div id="previewContainer" class="mt-3"></div>

                <!-- JUMLAH PER UKURAN + LENGAN -->
                <div class="mb-3">
                    <label class="fw-semibold mb-2 small">Jumlah per Ukuran & Lengan:</label>
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
                    <div class="d-flex gap-2">
                        <input type="file" id="uploadSablonInput" class="form-control" accept="image/*">
                        <button id="resetCanvas" class="btn btn-outline-secondary flex-shrink-0">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- TOTAL -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Total:</h5>
                    <h4 id="totalHarga" class="text-primary fw-bold mb-0">Rp 0</h4>
                </div>

                <!-- TOMBOL -->
                <div class="d-flex gap-3">
                    <form method="POST" action="{{ route('users.cart.store') }}" id="cartForm" class="flex-fill">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                        <input type="hidden" name="warna" id="warnaHidden">
                        <input type="hidden" name="bahan" id="bahanHidden">
                        <input type="hidden" name="lengan" id="lenganHidden">
                        <input type="hidden" name="detail_json" id="detailJson">
                        <input type="hidden" name="custom_sablon_data" id="customSablonData">

                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                            <i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== STYLE ========== --}}
    <style>
        .color-option {
            width: 30px;
            height: 30px;
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
            border: 1px solid #474747;
            transform: scale(1.05);
        }

        .mockup-stage {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            border-radius: 10px;
            background: #f8f9fa;
            isolation: isolate;
        }

        .mockup-color {
            display: none;
        }

        .mockup-img {
            position: absolute;
            inset: 0;
            z-index: 3;
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
            filter: grayscale(1);
        }


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
    </style>
@endsection

{{-- ========== SCRIPTS ========== --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const colorOptions = document.querySelectorAll('.color-option');
            const colorLayer = document.getElementById('colorLayer');
            const mockupThumbs = document.querySelectorAll('.mockup-thumb');
            const mockupImg = document.getElementById('mockupImg');
            const bahanSelect = document.getElementById('bahanSelect');
            const qtyInputs = document.querySelectorAll('.qty-input');
            const totalHargaEl = document.getElementById('totalHarga');
            const warnaHidden = document.getElementById('warnaHidden');
            const bahanHidden = document.getElementById('bahanHidden');
            const lenganHidden = document.getElementById('lenganHidden');
            const detailJson = document.getElementById('detailJson');
            const cartForm = document.getElementById('cartForm');
            const basePrice = Number({{ $produk->harga }}) || 0;

            // ===== CUSTOM SABLON LANGSUNG DI MOCKUP =====
            const canvas = document.getElementById('sablonCanvas');
            const ctx = canvas.getContext('2d');
            let backgroundImg = null;
            let uploadedImg = null;
            let imgX = 150,
                imgY = 150,
                imgW = 200,
                imgH = 200;
            let dragging = false,
                offsetX, offsetY;

            const mockupImgEl = document.getElementById('mockupImg');
            backgroundImg = new Image();
            backgroundImg.crossOrigin = "anonymous";
            backgroundImg.onload = () => redrawCanvas();
            backgroundImg.src = mockupImgEl.src;

            function redrawCanvas() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                if (!backgroundImg) return;

                ctx.drawImage(backgroundImg, 0, 0, canvas.width, canvas.height);

                const activeColor = document.querySelector('.color-option.active');
                if (activeColor) {
                    const hex = activeColor.dataset.hex || '#c9c9c9';

                    const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const data = imgData.data;

                    const r = parseInt(hex.substr(1, 2), 16);
                    const g = parseInt(hex.substr(3, 2), 16);
                    const b = parseInt(hex.substr(5, 2), 16);

                    for (let i = 0; i < data.length; i += 4) {
                        const alpha = data[i + 3];
                        if (alpha > 30) {
                            data[i] = (data[i] * 0.3 + r * 0.7);
                            data[i + 1] = (data[i + 1] * 0.3 + g * 0.7);
                            data[i + 2] = (data[i + 2] * 0.3 + b * 0.7);
                        }
                    }

                    ctx.putImageData(imgData, 0, 0);
                }

                if (uploadedImg) ctx.drawImage(uploadedImg, imgX, imgY, imgW, imgH);
            }

            document.getElementById('uploadSablonInput').addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;
                const img = new Image();
                img.onload = function() {
                    uploadedImg = img;
                    const scale = Math.min(canvas.width / img.width, canvas.height / img.height) * 0.5;
                    imgW = img.width * scale;
                    imgH = img.height * scale;
                    imgX = (canvas.width - imgW) / 2;
                    imgY = (canvas.height - imgH) / 2;
                    redrawCanvas();
                };
                img.src = URL.createObjectURL(file);
            });

            canvas.addEventListener('mousedown', (e) => {
                if (!uploadedImg) return;
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left,
                    y = e.clientY - rect.top;
                if (x >= imgX && x <= imgX + imgW && y >= imgY && y <= imgY + imgH) {
                    dragging = true;
                    offsetX = x - imgX;
                    offsetY = y - imgY;
                }
            });
            canvas.addEventListener('mousemove', (e) => {
                if (!dragging) return;
                const rect = canvas.getBoundingClientRect();
                imgX = e.clientX - rect.left - offsetX;
                imgY = e.clientY - rect.top - offsetY;
                redrawCanvas();
            });
            canvas.addEventListener('mouseup', () => dragging = false);
            canvas.addEventListener('mouseleave', () => dragging = false);

            canvas.addEventListener('wheel', (e) => {
                if (!uploadedImg) return;
                e.preventDefault();
                const scaleFactor = e.deltaY < 0 ? 1.05 : 0.95;
                imgW *= scaleFactor;
                imgH *= scaleFactor;
                redrawCanvas();
            });

            document.getElementById('resetCanvas').addEventListener('click', () => {
                uploadedImg = null;
                redrawCanvas();
            });

            document.getElementById('saveSablonBtn').addEventListener('click', async () => {
                if (!uploadedImg) {
                    Swal.fire('Belum ada gambar sablon!', '', 'warning');
                    return;
                }

                const imageData = canvas.toDataURL('image/png');
                const mockupId = mockupImgEl.dataset.id || 1;
                const pesananItemId = 1;
                const csrf = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrf ? csrf.content : '{{ csrf_token() }}';

                try {
                    const response = await fetch('/custom-sablon/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            image_data: imageData,
                            pesanan_item_id: pesananItemId,
                            mockup_id: mockupId
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Desain disimpan!',
                            text: 'Preview berhasil dibuat.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        const preview = document.createElement('img');
                        preview.src = result.preview_url;
                        preview.className = 'mt-3 rounded shadow-sm w-100';
                        preview.style.objectFit = 'contain';
                        const container = document.getElementById('previewContainer');
                        container.innerHTML = '';
                        container.appendChild(preview);

                        document.getElementById('customSablonData').value = result.preview_url;
                    } else {
                        throw new Error('Gagal menyimpan sablon.');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('Gagal menyimpan desain', err.message, 'error');
                }
            });


            const applyMaskFromImg = () => {
                const src = mockupImg.src;
                colorLayer.style.webkitMaskImage = `url('${src}')`;
                colorLayer.style.maskImage = `url('${src}')`;
            };

            mockupThumbs.forEach(img => {
                img.addEventListener('click', () => {
                    mockupThumbs.forEach(t => t.classList.remove('active'));
                    img.classList.add('active');
                    mockupImg.src = img.src;
                    backgroundImg.src = img.src;
                    applyMaskFromImg();
                });
            });

            applyMaskFromImg();

            colorOptions.forEach(el => {
                el.addEventListener('click', () => {
                    colorOptions.forEach(c => c.classList.remove('active'));
                    el.classList.add('active');
                    warnaHidden.value = el.dataset.nama || el.dataset.hex || '#c9c9c9';
                    redrawCanvas(); // langsung update canvas
                });
            });



            if (colorOptions.length) {
                const first = colorOptions[0];
                first.classList.add('active');
                colorLayer.style.backgroundColor = first.dataset.hex || '#c9c9c9';
                warnaHidden.value = first.dataset.nama || (first.dataset.hex || '#c9c9c9');
            }

            // ===== TOTAL =====
            function updateTotal() {
                const bahanHarga = parseFloat(bahanSelect.selectedOptions[0]?.dataset.harga || 0);
                let total = 0,
                    detail = [],
                    lastLengan = '';

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
                        lastLengan = input.dataset.lengan;
                    }
                });

                detailJson.value = JSON.stringify(detail);
                totalHargaEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
                bahanHidden.value = bahanSelect.value;
                lenganHidden.value = lastLengan;
            }

            bahanSelect.addEventListener('change', updateTotal);
            qtyInputs.forEach(i => i.addEventListener('input', updateTotal));

            // ===== VALIDASI =====
            cartForm.addEventListener('submit', function(e) {
                updateTotal();

                if (!warnaHidden.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Pilih Warna!',
                        text: 'Silakan pilih warna produk dulu.'
                    });
                    return;
                }
                if (!bahanSelect.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Pilih Bahan!',
                        text: 'Silakan pilih bahan terlebih dulu.'
                    });
                    return;
                }
                const detail = JSON.parse(detailJson.value || '[]');
                if (detail.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Belum Ada Ukuran!',
                        text: 'Masukkan minimal 1 qty.'
                    });
                }
            });
        });
    </script>
@endpush
