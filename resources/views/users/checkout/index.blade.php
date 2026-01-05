@extends('layouts.homepage')

@section('title', 'Checkout - Toko Delapan')

@section('content')
    <div class="container my-5">
        <h2 class="fw-bold mb-4 text-center text-primary animate-fade">
            🛍️ Checkout
        </h2>

        <form action="{{ route('users.placeOrder') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="row g-4">

                <!-- ==================== ALAMAT PENGIRIMAN ==================== -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-light fw-semibold rounded-top-4">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i> Alamat Pengiriman
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Nama Penerima</label>
                                <input type="text" name="nama_penerima" class="form-control form-control-sm rounded-3"
                                    value="{{ Auth::user()->nama ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Telepon</label>
                                <input type="text" name="telepon" class="form-control form-control-sm rounded-3"
                                    value="{{ Auth::user()->customer->no_hp ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control form-control-sm rounded-3" rows="3" required></textarea>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <select id="province" name="province_id" class="form-select form-select-sm">
                                        <option value="">Pilih Provinsi</option>
                                    </select>

                                </div>
                                <div class="col-md-6">
                                    <select id="city" name="city_id" class="form-select form-select-sm">
                                        <option value="">Pilih Kota</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <select id="district" class="form-select form-select-sm">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <select id="subdistrict" name="subdistrict_id" class="form-select form-select-sm">
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <select id="courier" class="form-select form-select-sm">
                                        <option value="">Pilih Kurir</option>
                                        <option value="jne">JNE</option>
                                        <option value="pos">POS</option>
                                        <option value="tiki">TIKI</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <select id="service" class="form-select form-select-sm">
                                        <option value="">Pilih Layanan</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== RINGKASAN PESANAN ==================== -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-light fw-semibold rounded-top-4">
                            <i class="fas fa-receipt me-2 text-primary"></i> Ringkasan Belanja
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush mb-3">
                                @foreach ($keranjang->items as $item)
                                    <li
                                        class="list-group-item px-0 d-flex justify-content-between align-items-start border-bottom">
                                        <div>
                                            <div class="d-flex align-items-center gap-3">
                                                @if ($item->custom_sablon_url)
                                                    <div class="mt-2">
                                                        <img src="{{ asset($item->custom_sablon_url) }}"
                                                            class="rounded border"
                                                            style="width:100px; height:100px; object-fit:contain;">
                                                    </div>
                                                @else
                                                    <img src="{{ asset('storage/' . ($item->produk->mockup->first()->file_path ?? 'placeholder.png')) }}"
                                                        class="rounded shadow-sm" width="70" height="70"
                                                        style="object-fit:cover;" alt="">
                                                @endif
                                                <div>
                                                    <h6 class="fw-semibold mb-1">{{ $item->produk->nama }}</h6>
                                                    <p class="text-muted small mb-0">
                                                        {{ ucfirst($item->produk->jenis_produk) }}</p>
                                                </div>

                                                <div class="small text-muted mt-1">
                                                    @if ($item->warna)
                                                        <span class="badge bg-light text-dark">Warna:
                                                            {{ $item->warna }}</span>
                                                    @endif
                                                    @if ($item->bahan)
                                                        <span class="badge bg-light text-dark">Bahan:
                                                            {{ $item->bahan }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <ul class="list-unstyled small mt-2 mb-0">
                                                @foreach ($item->details as $d)
                                                    <li>Uk. <strong>{{ strtoupper($d->ukuran) }} </strong>
                                                        <small>{{ $d->lengan }}</small> ×
                                                        {{ $d->qty }}
                                                        <span class="text-muted">(Rp
                                                            {{ number_format($d->subtotal, 0, ',', '.') }})</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @php
                                                $tambahan = is_string($item->rincian_tambahan)
                                                    ? json_decode($item->rincian_tambahan, true)
                                                    : $item->rincian_tambahan;
                                            @endphp

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
                                                            <li class="mt-1">
                                                                <strong>Sablon:</strong>
                                                                <ul class="mb-0 ps-3">
                                                                    @foreach ($tambahan['sablon'] as $s)
                                                                        <li>
                                                                            {{ $s['type'] }} {{ $s['sizeLabel'] }}
                                                                            <br>
                                                                            <small class="text-muted">
                                                                                Rp
                                                                                {{ number_format($s['cost'], 0, ',', '.') }}
                                                                                × {{ $s['qty'] }} pcs
                                                                                = Rp
                                                                                {{ number_format($s['subtotal'], 0, ',', '.') }}
                                                                            </small>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @endif
                                                    </ul>

                                                    @php
                                                        $totalTambahan =
                                                            ($tambahan['bahan']['total'] ?? 0) +
                                                            collect($tambahan['sablon'])->sum('cost');
                                                    @endphp
                                                    <div class="text-success mt-1">
                                                        <strong>Total Tambahan:</strong> +Rp
                                                        {{ number_format($totalTambahan, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="text-end fw-semibold text-primary">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Input Voucher -->
                            <div class="input-group input-group-sm mb-3">
                                <input type="text" class="form-control rounded-start-3"
                                    placeholder="Masukkan kode voucher">
                                <button type="button" id="btnVoucher" class="btn btn-outline-primary rounded-end-3">
                                    <i class="fas fa-ticket-alt me-1"></i> Gunakan
                                </button>
                            </div>

                            <!-- Subtotal -->
                            <div class="d-flex justify-content-between small mb-2">
                                <span>Subtotal</span>
                                <strong>Rp {{ number_format($keranjang->items->sum('subtotal'), 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between small mb-2">
                                <span>Ongkir</span>
                                <strong id="ongkir-text">Rp 0</strong>
                            </div>

                            <input type="hidden" name="ongkir" id="ongkir" value="0">


                            <div class="d-flex justify-content-between small mb-2 text-success d-none" id="row-diskon">
                                <span>Diskon Voucher</span>
                                <strong id="diskon-value">- Rp 0</strong>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between fs-5 fw-bold text-primary">
                                <span>Total</span>
                                <span id="total-value">Rp
                                    {{ number_format($keranjang->items->sum('subtotal') + 20000, 0, ',', '.') }}</span>
                            </div>

                            <button class="btn btn-primary w-100 mt-4 rounded-pill shadow-sm">
                                <i class="fas fa-credit-card me-1"></i> Buat Pesanan
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <style>
        .animate-fade {
            animation: fadeInUp .6s ease-in-out;
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

        .list-group-item {
            background: transparent;
        }

        .list-group-item:hover {
            background-color: #f9faff;
            transition: .2s;
        }

        .card-header {
            border-bottom: 1px solid #eee !important;
        }

        textarea,
        input {
            box-shadow: none !important;
        }

        .btn:focus {
            box-shadow: none !important;
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(function() {
                const $voucherInput = $('.input-group input[type="text"]');
                const ORIGIN_SUBDISTRICT_ID = 31511;
                const $voucherBtn = $('#btnVoucher');
                const $diskonRow = $('#row-diskon');
                const $diskonValue = $('#diskon-value');
                const $totalValue = $('#total-value');
                const subtotal = {{ $keranjang->items->sum('subtotal') }};
                const ongkir = 20000;


                // === Load provinsi
                $.get('/ongkir/provinces', function(data) {
                    $('#province').html('<option value="">Pilih Provinsi</option>');
                    data.forEach(p => {
                        $('#province').append(
                            `<option value="${p.name}" data-id="${p.id}">${p.name}</option>`
                        );
                    });
                });

                // === Kota
                $('#province').on('change', function() {
                    const provinceId = $(this).find(':selected').data('id');
                    $('#city').html('<option value="">Pilih Kota</option>');
                    if (!provinceId) return;

                    $.get(`/ongkir/cities/${provinceId}`, function(data) {
                        data.forEach(c => {
                            $('#city').append(
                                `<option value="${c.name}" data-id="${c.id}">${c.name}</option>`
                            );
                        });
                    });
                });

                // === Kecamatan
                $('#city').on('change', function() {
                    const cityId = $(this).find(':selected').data('id');
                    $('#district').html('<option value="">Pilih Kecamatan</option>');
                    $('#subdistrict').html('<option value="">Pilih Kelurahan</option>');
                    if (!cityId) return;

                    $.get(`/ongkir/districts/${cityId}`, function(data) {
                        data.forEach(d => {
                            $('#district').append(
                                `<option value="${d.name}" data-id="${d.id}">${d.name}</option>`
                            );
                        });
                    });
                });

                // === Kelurahan
                $('#district').on('change', function() {
                    const districtId = $(this).find(':selected').data('id');
                    $('#subdistrict').html('<option value="">Pilih Kelurahan</option>');
                    if (!districtId) return;

                    $.get(`/ongkir/subdistricts/${districtId}`, function(data) {
                        data.forEach(s => {
                            $('#subdistrict').append(
                                `<option value="${s.name}" data-id="${s.id}">${s.name}</option>`
                            );
                        });
                    });
                });

                // === Ongkir
                $('#courier').on('change', function() {
                    const courier = $(this).val();
                    const destinationId = $('#subdistrict option:selected').data('id');

                    if (!destinationId) {
                        alert('Pilih kelurahan dulu');
                        return;
                    }

                    $.post('/ongkir/cost', {
                        _token: '{{ csrf_token() }}',
                        origin: ORIGIN_SUBDISTRICT_ID,
                        destination: destinationId,
                        weight: 1000,
                        courier: courier
                    }, function(services) {
                        console.log('Data layanan:', services); // log untuk cek

                        $('#service').html('<option value="">Pilih Layanan</option>');
                        services.forEach(s => {
                            $('#service').append(`
                    <option value="${s.cost}">
                        ${s.service} - Rp ${s.cost.toLocaleString('id-ID')}
                    </option>
                `);
                        });
                    });
                });

                // === Pilih layanan
                $('#service').on('change', function() {
                    const cost = parseInt($(this).val());
                    $('#ongkir').val(cost);
                    $('#ongkir-text').text('Rp ' + cost.toLocaleString('id-ID'));
                    updateTotal();
                });


                function updateTotal(diskon = 0) {
                    const ongkirVal = parseInt($('#ongkir').val() || 0);
                    const total = subtotal + ongkirVal - diskon;
                    $('#total-value').text("Rp " + total.toLocaleString('id-ID'));
                }

                // === APPLY VOUCHER ===
                $voucherBtn.on('click', function() {
                    const kode = $voucherInput.val().trim();
                    if (!kode) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops!',
                            text: 'Masukkan kode voucher terlebih dahulu.'
                        });
                        return;
                    }

                    $.ajax({
                        url: "{{ route('users.checkVoucher') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode,
                            subtotal
                        },
                        success: function(res) {
                            if (res.success) {
                                const diskon = res.diskon;
                                updateTotal(diskon);

                                $diskonRow.removeClass('d-none');
                                $diskonValue.text("- Rp " + diskon.toLocaleString('id-ID'));
                                $totalValue.text("Rp " + total.toLocaleString('id-ID'));

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Voucher berhasil digunakan!',
                                    text: `Diskon Rp ${diskon.toLocaleString('id-ID')}`,
                                    timer: 1800,
                                    showConfirmButton: false
                                });

                                if (!$('#voucher_kode_hidden').length) {
                                    $('<input>').attr({
                                        type: 'hidden',
                                        name: 'voucher_kode',
                                        value: kode,
                                        id: 'voucher_kode_hidden'
                                    }).appendTo('form');
                                } else {
                                    $('#voucher_kode_hidden').val(kode);
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Voucher tidak valid!',
                                    text: res.message ||
                                        'Kode voucher tidak dapat digunakan.'
                                });
                                $diskonRow.addClass('d-none');
                                $diskonValue.text("- Rp 0");
                                $totalValue.text("Rp " + (subtotal + ongkir).toLocaleString(
                                    'id-ID'));
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi kesalahan!',
                                text: 'Gagal memeriksa voucher. Coba lagi nanti.'
                            });
                        }
                    });
                });

                // === CONFIRMATION ON SUBMIT ===
                $('#checkoutForm').on('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Buat pesanan sekarang?',
                        text: 'Pastikan alamat dan data pesanan sudah benar.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0d6efd',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, buat pesanan',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (result.isConfirmed) {
                            $('#checkoutForm').append(
                                `<input type="hidden" name="provinsi" value="${$('#province').val()}">`
                            );
                            $('#checkoutForm').append(
                                `<input type="hidden" name="kota" value="${$('#city').val()}">`);
                            $('#checkoutForm').append(
                                `<input type="hidden" name="kecamatan" value="${$('#district').val()}">`
                            );
                            $('#checkoutForm').append(
                                `<input type="hidden" name="kelurahan" value="${$('#subdistrict').val()}">`
                            );
                            e.target.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
