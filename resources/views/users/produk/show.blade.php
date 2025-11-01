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
                        <button id="deleteActiveSablon" class="btn btn-outline-danger flex-shrink-0">
                            <i class="fas fa-trash me-1"></i> Hapus Aktif
                        </button>
                    </div>

                    <div class="my-2">
                        <input type="text" id="textInput" class="form-control" placeholder="Tulis teks sablon...">
                        <select id="fontSelect" class="form-control mt-2">
                            <option value="Arial">Arial</option>
                            <option value="Poppins">Poppins</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Montserrat">Montserrat</option>
                            <option value="Courier New">Courier New</option>
                            <option value="Pacifico">Pacifico</option>
                        </select>
                        <button id="addTextBtn" class="btn btn-outline-dark mt-2">
                            <i class="fas fa-font me-1"></i> Tambah Teks
                        </button>
                    </div>

                    <div id="textTools" class="mt-2 d-none">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <label class="fw-semibold small mb-0">Warna:</label>
                            <input type="color" id="textColor" value="#ffffff" class="form-control form-control-color"
                                style="width: 60px;">

                            <label class="fw-semibold small mb-0">Ukuran:</label>
                            <input type="range" id="textSize" min="10" max="200" value="32"
                                style="width: 120px;">

                            <button id="boldToggle" class="btn btn-outline-secondary btn-sm"><i
                                    class="fas fa-bold"></i></button>
                            <button id="italicToggle" class="btn btn-outline-secondary btn-sm"><i
                                    class="fas fa-italic"></i></button>
                            <button id="alignLeft" class="btn btn-outline-secondary btn-sm"><i
                                    class="fas fa-align-left"></i></button>
                            <button id="alignCenter" class="btn btn-outline-secondary btn-sm active"><i
                                    class="fas fa-align-center"></i></button>
                            <button id="alignRight" class="btn btn-outline-secondary btn-sm"><i
                                    class="fas fa-align-right"></i></button>
                        </div>
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
                        <input type="hidden" name="biaya_tambahan_total" id="biayaTambahanTotal">
                        <input type="hidden" name="rincian_tambahan" id="rincianTambahan">

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
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            background-color: var(--mockup-color, #ccc);
            -webkit-mask-repeat: no-repeat;
            -webkit-mask-position: center;
            -webkit-mask-size: contain;
            mask-repeat: no-repeat;
            mask-position: center;
            mask-size: contain;
        }


        .mockup-img {
            position: absolute;
            inset: 0;
            z-index: 3;
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
        }

        #sablonCanvas {
            position: absolute;
            inset: 0;
            z-index: 5;
            width: 100%;
            height: 100%;
            cursor: move;
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

            // ===== BOX INFORMASI BIAYA TAMBAHAN =====
            const tambahanInfoEl = document.createElement('div');
            tambahanInfoEl.className = 'p-2 mt-3 mb-3 border rounded bg-light small';
            tambahanInfoEl.innerHTML = `<strong>Biaya Tambahan:</strong> <span class="text-muted">Belum ada</span>`;
            document.querySelector('.d-flex.justify-content-between.align-items-center.mb-3')
                .insertAdjacentElement('beforebegin', tambahanInfoEl);

            function updateTambahanInfo(bahanHarga, bahanNama, sablonCost, sablonBreakdown, detail) {
                let html = `
    <strong>Rincian Biaya Tambahan:</strong>
    <div class="mt-2">
        <table class="table table-sm table-borderless mb-0">
            <tbody>
    `;
                let totalTambahan = 0;
                const totalQty = detail.reduce((sum, d) => sum + d.qty, 0);

                if (bahanHarga > 0 && bahanNama && totalQty > 0) {
                    const bahanTotal = bahanHarga * totalQty;
                    html += `
            <tr>
                <td class="text-muted small">Bahan:</td>
                <td>${bahanNama} × ${totalQty} pcs</td>
                <td class="text-end text-primary fw-semibold">+Rp ${bahanTotal.toLocaleString('id-ID')}</td>
            </tr>`;
                    totalTambahan += bahanTotal;
                }

                const kombinasi = {};
                detail.forEach(d => {
                    const tambahan = d.harga_satuan;
                    if (tambahan > 0) {
                        const key = `${d.ukuran}-${d.lengan}`;
                        if (!kombinasi[key]) kombinasi[key] = {
                            ukuran: d.ukuran,
                            lengan: d.lengan,
                            tambahan: 0,
                            qty: 0,
                            perItem: tambahan
                        };
                        kombinasi[key].tambahan += tambahan * d.qty;
                        kombinasi[key].qty += d.qty;
                    }
                });

                Object.values(kombinasi).forEach(k => {
                    const hargaDasar = basePrice + bahanHarga;
                    html += `
    <tr>
      <td></td>
      <td>Ukuran ${k.ukuran} (${k.lengan}) × ${k.qty} pcs @Rp ${k.perItem.toLocaleString('id-ID')}
          <br><small class="text-muted">(Base Rp ${hargaDasar.toLocaleString('id-ID')})</small></td>
      <td class="text-end text-primary">Rp ${(k.perItem * k.qty).toLocaleString('id-ID')}</td>
    </tr>`;
                    totalTambahan += k.perItem * k.qty;
                });

                if (sablonBreakdown.length > 0) {
                    html += `
            <tr><td colspan="3" class="pt-2 pb-0 fw-semibold">Custom Sablon:</td></tr>
        `;
                    sablonBreakdown.forEach((item, i) => {
                        html += `
                <tr>
                    <td></td>
                    <td>${i + 1}. ${item.type} ${item.sizeLabel}</td>
                    <td class="text-end text-primary">+Rp ${item.cost.toLocaleString('id-ID')}</td>
                </tr>`;
                    });
                    html += `
            <tr>
                <td></td>
                <td class="fw-semibold">Subtotal sablon (${sablonBreakdown.length} item)</td>
                <td class="text-end text-primary fw-semibold">+Rp ${sablonCost.toLocaleString('id-ID')}</td>
            </tr>`;
                    totalTambahan += sablonCost;
                }

                html += `
           <tr class="border-top">
  <td colspan="2" class="fw-bold pt-2">Total Keseluruhan</td>
  <td class="text-end fw-bold text-primary pt-2">Rp ${totalTambahan.toLocaleString('id-ID')}</td>
</tr>
        </tbody>
    </table>
    </div>
    `;
                tambahanInfoEl.innerHTML = html;
            }

            const warnaHidden = document.getElementById('warnaHidden');
            const bahanHidden = document.getElementById('bahanHidden');
            const lenganHidden = document.getElementById('lenganHidden');
            const detailJson = document.getElementById('detailJson');
            const cartForm = document.getElementById('cartForm');
            const basePrice = Number({{ $produk->harga }}) || 0;

            // ===== CUSTOM SABLON MULTI GAMBAR =====
            const canvas = document.getElementById('sablonCanvas');
            const ctx = canvas.getContext('2d');
            let backgroundImg = new Image();
            let uploadedImages = [];
            let activeImageIndex = -1;
            let uploadedTexts = []; // simpan teks sablon
            let activeTextIndex = -1;

            const mockupImgEl = document.getElementById('mockupImg');
            backgroundImg.crossOrigin = "anonymous";
            backgroundImg.onload = () => redrawCanvas();
            backgroundImg.src = mockupImgEl.src;

            function redrawCanvas(showOutline = true) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                if (backgroundImg) ctx.drawImage(backgroundImg, 0, 0, canvas.width, canvas.height);

                // pewarnaan kaos
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

                // gambar gambar sablon
                uploadedImages.forEach((imgObj, i) => {
                    ctx.globalAlpha = (i === activeImageIndex) ? 1 : 0.9;
                    ctx.drawImage(imgObj.img, imgObj.x, imgObj.y, imgObj.w, imgObj.h);
                    if (showOutline && i === activeImageIndex) {
                        ctx.save();
                        ctx.strokeStyle = 'rgba(255,0,0,0.6)';
                        ctx.lineWidth = 2;
                        ctx.setLineDash([6, 3]);
                        ctx.strokeRect(imgObj.x, imgObj.y, imgObj.w, imgObj.h);
                        ctx.restore();
                    }
                });

                // gambar teks
                uploadedTexts.forEach((txtObj, i) => {
                    const fontStyle =
                        `${txtObj.italic ? 'italic ' : ''}${txtObj.bold ? 'bold ' : ''}${txtObj.size}px ${txtObj.font}`;
                    ctx.font = fontStyle;
                    ctx.fillStyle = txtObj.color;
                    ctx.textAlign = txtObj.align;
                    ctx.textBaseline = 'middle';
                    ctx.fillText(txtObj.text, txtObj.x, txtObj.y);

                    if (showOutline && i === activeTextIndex) {
                        const width = ctx.measureText(txtObj.text).width;
                        ctx.save();
                        ctx.strokeStyle = 'rgba(0,123,255,0.7)';
                        ctx.setLineDash([4, 2]);
                        ctx.strokeRect(
                            txtObj.x - (txtObj.align === 'center' ? width / 2 : txtObj.align ===
                                'right' ? width : 0) - 5,
                            txtObj.y - txtObj.size / 2,
                            width + 10,
                            txtObj.size + 6
                        );
                        ctx.restore();
                    }
                });

                ctx.globalAlpha = 1;
            }

            document.getElementById('addTextBtn').addEventListener('click', () => {
                const text = document.getElementById('textInput').value.trim();
                const font = document.getElementById('fontSelect').value;
                if (!text) return Swal.fire('Tulis dulu teksnya!', '', 'warning');

                const size = 32;
                const cost = size > 50 ? 10000 : 5000;
                const sizeLabel = size > 50 ? '(besar)' : '(kecil)';
                sablonCost += cost;
                sablonBreakdown.push({
                    type: 'Teks',
                    cost,
                    sizeLabel
                });

                uploadedTexts.push({
                    text,
                    font,
                    x: canvas.width / 2,
                    y: canvas.height / 2,
                    size,
                    color: '#ffffff',
                    bold: false,
                    italic: false,
                    align: 'center',
                    cost
                });
                activeTextIndex = uploadedTexts.length - 1;
                document.getElementById('textTools').classList.remove('d-none');

                updateSablonInfo();
                redrawCanvas();
            });

            // tambah sablon baru
            document.getElementById('uploadSablonInput').addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;

                const img = new Image();
                img.onload = function() {
                    const scale = Math.min(canvas.width / img.width, canvas.height / img.height) * 0.5;
                    const w = img.width * scale;
                    const h = img.height * scale;
                    const x = (canvas.width - w) / 2;
                    const y = (canvas.height - h) / 2;

                    // Hitung biaya sablon gambar
                    const area = w * h;
                    const threshold = (canvas.width * canvas.height) / 4;
                    const cost = area > threshold ? 10000 : 5000;
                    const sizeLabel = area > threshold ? '(besar)' : '(kecil)';
                    sablonCost += cost;
                    sablonBreakdown.push({
                        type: 'Gambar',
                        cost,
                        sizeLabel
                    });

                    uploadedImages.push({
                        img,
                        x,
                        y,
                        w,
                        h,
                        cost
                    });
                    activeImageIndex = uploadedImages.length - 1;

                    updateSablonInfo();
                    redrawCanvas();
                };
                img.src = URL.createObjectURL(file);
            });

            let dragging = false,
                offsetX, offsetY;

            canvas.addEventListener('mousedown', (e) => {
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left,
                    y = e.clientY - rect.top;
                dragging = false;

                for (let i = uploadedTexts.length - 1; i >= 0; i--) {
                    ctx.font = `${uploadedTexts[i].size}px ${uploadedTexts[i].font}`;
                    const width = ctx.measureText(uploadedTexts[i].text).width;
                    const height = uploadedTexts[i].size;
                    if (x >= uploadedTexts[i].x - width / 2 && x <= uploadedTexts[i].x + width / 2 &&
                        y >= uploadedTexts[i].y - height && y <= uploadedTexts[i].y) {
                        activeTextIndex = i;
                        activeImageIndex = -1;
                        document.getElementById('textTools').classList.remove('d-none');

                        const txt = uploadedTexts[i];
                        textColor.value = txt.color;
                        textSize.value = txt.size;
                        boldToggle.classList.toggle('btn-primary', txt.bold);
                        italicToggle.classList.toggle('btn-primary', txt.italic);
                        [alignLeft, alignCenter, alignRight].forEach(b => b.classList.remove(
                            'btn-primary'));
                        document.getElementById('align' + txt.align.charAt(0).toUpperCase() + txt.align
                                .slice(1))
                            .classList.add('btn-primary');

                        dragging = 'text';
                        offsetX = x - txt.x;
                        offsetY = y - txt.y;

                        redrawCanvas();
                        return;
                    }

                }

                for (let i = uploadedImages.length - 1; i >= 0; i--) {
                    const im = uploadedImages[i];
                    if (x >= im.x && x <= im.x + im.w && y >= im.y && y <= im.y + im.h) {
                        activeImageIndex = i;
                        activeTextIndex = -1;
                        dragging = 'image';
                        offsetX = x - im.x;
                        offsetY = y - im.y;
                        redrawCanvas();
                        return;
                    }
                }
            });

            canvas.addEventListener('mousemove', (e) => {
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                let hover = false;

                for (let i = uploadedImages.length - 1; i >= 0; i--) {
                    const im = uploadedImages[i];
                    if (x >= im.x && x <= im.x + im.w && y >= im.y && y <= im.y + im.h) {
                        hover = true;
                        break;
                    }
                }

                if (!hover) {
                    for (let i = uploadedTexts.length - 1; i >= 0; i--) {
                        ctx.font = `${uploadedTexts[i].size}px ${uploadedTexts[i].font}`;
                        const width = ctx.measureText(uploadedTexts[i].text).width;
                        const height = uploadedTexts[i].size;
                        if (x >= uploadedTexts[i].x - width / 2 && x <= uploadedTexts[i].x + width / 2 &&
                            y >= uploadedTexts[i].y - height && y <= uploadedTexts[i].y) {
                            hover = true;
                            break;
                        }
                    }
                }

                canvas.style.cursor = hover ? 'move' : 'default';

                if (dragging) {
                    if (dragging === 'image' && activeImageIndex >= 0) {
                        const im = uploadedImages[activeImageIndex];
                        im.x = x - offsetX;
                        im.y = y - offsetY;
                    }

                    if (dragging === 'text' && activeTextIndex >= 0) {
                        const tx = uploadedTexts[activeTextIndex];
                        tx.x = x - offsetX;
                        tx.y = y - offsetY;
                    }

                    redrawCanvas();
                }
            });


            canvas.addEventListener('mouseup', () => dragging = false);
            canvas.addEventListener('mouseleave', () => dragging = false);


            canvas.addEventListener('wheel', (e) => {
                e.preventDefault();

                // Zoom gambar aktif
                if (activeImageIndex >= 0) {
                    const imgObj = uploadedImages[activeImageIndex];
                    const scale = e.deltaY < 0 ? 1.1 :
                        0.9; // scroll ke atas = perbesar, ke bawah = perkecil
                    imgObj.w = Math.max(30, Math.min(canvas.width, imgObj.w * scale));
                    imgObj.h = Math.max(30, Math.min(canvas.height, imgObj.h * scale));
                    redrawCanvas();
                    return;
                }

                if (activeTextIndex >= 0) {
                    const txt = uploadedTexts[activeTextIndex];
                    const scale = e.deltaY < 0 ? 1.1 : 0.9;
                    txt.size = Math.max(10, Math.min(200, txt.size * scale));
                    redrawCanvas();
                }
            });


            document.getElementById('resetCanvas').addEventListener('click', () => {
                uploadedImages = [];
                activeImageIndex = -1;
                redrawCanvas();
            });

            document.getElementById('deleteActiveSablon').addEventListener('click', () => {
                if (activeImageIndex >= 0) {
                    uploadedImages.splice(activeImageIndex, 1);
                    activeImageIndex = -1;
                    redrawCanvas();
                    return;
                }

                if (activeTextIndex >= 0) {
                    uploadedTexts.splice(activeTextIndex, 1);
                    activeTextIndex = -1;
                    document.getElementById('textTools').classList.add('d-none');
                    redrawCanvas();
                    return;
                }

                Swal.fire('Tidak ada sablon atau teks aktif', '', 'info');
            });


            document.getElementById('saveSablonBtn').addEventListener('click', async () => {

                redrawCanvas(false);
                const imageData = canvas.toDataURL('image/png');
                redrawCanvas(true);

                const mockupId = mockupImgEl.dataset.id || 1;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                    '{{ csrf_token() }}';

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
                            mockup_id: mockupId
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Desain disimpan!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        const preview = document.createElement('img');
                        preview.src = result.preview_url;
                        preview.className = 'mt-3 rounded shadow-sm w-100';
                        preview.style.objectFit = 'contain';
                        document.getElementById('previewContainer').innerHTML = '';
                        document.getElementById('previewContainer').appendChild(preview);
                        document.getElementById('customSablonData').value = result.preview_url;
                    }
                } catch (err) {
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
                    redrawCanvas();
                });
            });



            if (colorOptions.length) {
                const first = colorOptions[0];
                first.classList.add('active');
                colorLayer.style.backgroundColor = first.dataset.hex || '#c9c9c9';
                warnaHidden.value = first.dataset.nama || (first.dataset.hex || '#c9c9c9');
            }


            let sablonCost = 0;
            let sablonBreakdown = [];

            const sablonInfoEl = document.createElement('div');
            sablonInfoEl.className = 'p-2 mt-2 mb-3 border rounded bg-light small';

            // ===== TOTAL =====
            function updateTotal() {
                const bahanHarga = parseFloat(bahanSelect.selectedOptions[0]?.dataset.harga || 0);
                const bahanNama = bahanSelect.value || null;
                let total = 0,
                    detail = [],
                    lastLengan = '';

                qtyInputs.forEach(input => {
                    const qty = parseInt(input.value || 0);
                    const hargaTambahan = parseFloat(input.dataset.harga);
                    if (qty > 0) {
                        const harga = basePrice + hargaTambahan;
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

                const totalQty = detail.reduce((sum, d) => sum + d.qty, 0);
                const bahanTotal = bahanHarga * totalQty;

                total += bahanTotal + sablonCost;

                updateTambahanInfo(bahanHarga, bahanNama, sablonCost, sablonBreakdown, detail);

                totalHargaEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
                detailJson.value = JSON.stringify(detail);
                bahanHidden.value = bahanSelect.value;
                lenganHidden.value = lastLengan;

                document.getElementById('biayaTambahanTotal').value = (bahanTotal + sablonCost).toFixed(0);
                document.getElementById('rincianTambahan').value = JSON.stringify({
                    bahan: {
                        nama: bahanNama,
                        total: bahanTotal
                    },
                    sablon: sablonBreakdown,
                });
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

            // ========== KONTROL TEKS ==========
            const textColor = document.getElementById('textColor');
            const textSize = document.getElementById('textSize');
            const boldToggle = document.getElementById('boldToggle');
            const italicToggle = document.getElementById('italicToggle');
            const alignLeft = document.getElementById('alignLeft');
            const alignCenter = document.getElementById('alignCenter');
            const alignRight = document.getElementById('alignRight');

            textColor.addEventListener('input', () => {
                if (activeTextIndex >= 0) {
                    uploadedTexts[activeTextIndex].color = textColor.value;
                    redrawCanvas();
                }
            });

            textSize.addEventListener('input', () => {
                if (activeTextIndex >= 0) {
                    uploadedTexts[activeTextIndex].size = parseInt(textSize.value);
                    redrawCanvas();
                }
            });

            boldToggle.addEventListener('click', () => {
                if (activeTextIndex >= 0) {
                    const txt = uploadedTexts[activeTextIndex];
                    txt.bold = !txt.bold;
                    boldToggle.classList.toggle('btn-primary', txt.bold);
                    redrawCanvas();
                }
            });

            italicToggle.addEventListener('click', () => {
                if (activeTextIndex >= 0) {
                    const txt = uploadedTexts[activeTextIndex];
                    txt.italic = !txt.italic;
                    italicToggle.classList.toggle('btn-primary', txt.italic);
                    redrawCanvas();
                }
            });

            [alignLeft, alignCenter, alignRight].forEach(btn => {
                btn.addEventListener('click', () => {
                    if (activeTextIndex >= 0) {
                        const txt = uploadedTexts[activeTextIndex];
                        txt.align = btn.id === 'alignLeft' ? 'left' : btn.id === 'alignRight' ?
                            'right' : 'center';
                        [alignLeft, alignCenter, alignRight].forEach(b => b.classList.remove(
                            'btn-primary'));
                        btn.classList.add('btn-primary');
                        redrawCanvas();
                    }
                });
            });
        });
    </script>
@endpush
