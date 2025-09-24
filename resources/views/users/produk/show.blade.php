@extends('layouts.homepage')

@section('title', $produk->nama . ' - Toko Delapan')

@section('content')
    <style>
        .color-option {
            position: relative;
            cursor: pointer;
        }

        .color-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #ddd;
            display: inline-block;
            transition: all 0.2s ease;
        }

        .color-option input:checked+.color-circle {
            border: 3px solid #0d6efd;
            /* border biru */
            box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
            /* efek glow */
            position: relative;
        }

        .color-option input:checked+.color-circle::after {
            content: "✓";
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -55%);
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
        }
    </style>

    <div class="container my-4">
        <div class="row g-4">
            <!-- Gambar produk -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 position-relative overflow-hidden">
                    <img src="https://placehold.co/500x400" class="card-img-top rounded" alt="{{ $produk->nama }}"
                        id="produk-mockup">

                    <!-- Preview sablon -->
                    <img id="preview-sablon" src="" class="position-absolute d-none"
                        style="max-width:120px; opacity:0.9; pointer-events:none; transition: all 0.3s ease;">
                </div>
            </div>

            <!-- Info produk -->
            <div class="col-lg-7">
                <h4 class="fw-bold mb-2">{{ $produk->nama }}</h4>
                <p class="text-muted small">{{ $produk->deskripsi }}</p>
                <h5 class="text-primary fw-bold mb-4">
                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                </h5>

                <!-- Form -->
                <form action="{{ route('users.cart.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                    <div class="row g-3">
                        <!-- 1. Sablon -->
                        <div class="col-md-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light fw-semibold py-2 small">1. Sablon</div>
                                <div class="card-body p-3">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="noSablon" name="pakai_sablon" value="0" checked
                                            class="form-check-input">
                                        <label class="form-check-label small" for="noSablon">Tidak</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="yesSablon" name="pakai_sablon" value="1"
                                            class="form-check-input">
                                        <label class="form-check-label small" for="yesSablon">Ya</label>
                                    </div>

                                    <div id="sablon-options" class="d-none mt-2 border-top pt-2">
                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold">Posisi</label>
                                            <select name="sablon_posisi" class="form-select form-select-sm">
                                                <option value="">-- Pilih Posisi --</option>
                                                @foreach ($posisiSablon as $posisi)
                                                    <option value="{{ $posisi->kode }}">{{ $posisi->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold">Upload Desain</label>
                                            <input type="file" name="sablon_gambar" class="form-control form-control-sm"
                                                accept="image/*">
                                            <small class="text-muted">Format: JPG, PNG (max 2MB)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Ukuran -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light fw-semibold py-2 small">2. Ukuran</div>
                                <div class="card-body p-3">
                                    <select name="ukuran" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Ukuran --</option>
                                        @foreach ($produk->varian->pluck('ukuran')->unique()->sortBy(function ($u) {
                return ['S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4, 'XXL' => 5][$u] ?? 99;
            }) as $ukuran)
                                            <option value="{{ $ukuran }}">{{ strtoupper($ukuran) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Warna -->
                        <!-- 3. Warna -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light fw-semibold py-2 small">3. Warna</div>
                                <div class="card-body p-3">
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach ($produk->varian->pluck('warna')->unique() as $warna)
                                            <label class="color-option">
                                                <input type="radio" name="warna" value="{{ $warna }}" required
                                                    hidden>
                                                <span class="color-circle"
                                                    style="background-color: {{ $warna }}"></span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Lengan -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light fw-semibold py-2 small">4. Lengan</div>
                                <div class="card-body p-3">
                                    <select name="lengan" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Jenis Lengan --</option>
                                        @foreach ($produk->varian->pluck('lengan')->unique() as $lengan)
                                            <option value="{{ $lengan }}">{{ ucfirst($lengan) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Bahan -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light fw-semibold py-2 small">5. Bahan</div>
                                <div class="card-body p-3">
                                    <select name="material_id" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Bahan --</option>
                                        @foreach ($produk->varian->whereNotNull('material_id')->pluck('material_id')->unique() as $materialId)
                                            <option value="{{ $materialId }}">
                                                {{ $produk->varian->firstWhere('material_id', $materialId)?->material?->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Jumlah -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light fw-semibold py-2 small">6. Jumlah</div>
                                <div class="card-body p-3">
                                    <input type="number" name="qty" value="1" min="1"
                                        class="form-control form-control-sm w-auto" style="max-width:100px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm mt-3">
                        <i class="fas fa-shopping-cart me-1"></i> Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        jQuery(function($) {
            const $preview = $('#preview-sablon');
            const $sablonOptions = $('#sablon-options');

            // toggle sablon section
            $('input[name="pakai_sablon"]').on('change', function() {
                if ($(this).val() === "1") {
                    $sablonOptions.slideDown().removeClass('d-none');
                } else {
                    $sablonOptions.slideUp(function() {
                        $(this).addClass('d-none');
                        $('select[name="sablon_posisi"]').val('');
                        $('input[name="sablon_gambar"]').val('');
                        $preview.attr('src', '').addClass('d-none');
                    });
                }
            });

            // preview gambar sablon
            $('input[name="sablon_gambar"]').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = evt => {
                        $preview.attr('src', evt.target.result).removeClass('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // atur posisi sablon
            $('select[name="sablon_posisi"]').on('change', function() {
                const posisi = $(this).val();

                if (!posisi || !$preview.attr('src')) {
                    $preview.addClass('d-none');
                    return;
                }

                $preview.removeClass('d-none').css({
                    top: '',
                    left: '',
                    right: '',
                    bottom: '',
                    transform: ''
                });

                switch (posisi) {
                    case 'depan':
                        $preview.css({
                            top: '40%',
                            left: '50%',
                            transform: 'translate(-50%, -50%)'
                        });
                        break;
                    case 'belakang':
                        $preview.css({
                            top: '40%',
                            left: '50%',
                            transform: 'translate(-50%, -50%)'
                        });
                        break;
                    case 'lengan_kiri':
                        $preview.css({
                            top: '50%',
                            left: '20%',
                            transform: 'translate(-50%, -50%)'
                        });
                        break;
                    case 'lengan_kanan':
                        $preview.css({
                            top: '50%',
                            right: '20%',
                            transform: 'translate(50%, -50%)'
                        });
                        break;
                    default:
                        $preview.css({
                            top: '40%',
                            left: '50%',
                            transform: 'translate(-50%, -50%)'
                        });
                }
            });
        });
    </script>
@endpush
