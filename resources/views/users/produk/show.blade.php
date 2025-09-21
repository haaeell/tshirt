@extends('layouts.homepage')

@section('title', $produk->nama . ' - Toko Delapan')

@section('content')
<div class="container my-5">
    <div class="row g-4">
        <!-- Gambar produk dengan mockup sablon -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 position-relative">
                <img src="https://placehold.co/500x400"
                     class="card-img-top rounded"
                     alt="{{ $produk->nama }}" id="produk-mockup">

                <!-- Preview sablon -->
                <img id="preview-sablon"
                     src=""
                     class="position-absolute d-none"
                     style="max-width:150px; opacity:0.9;">
            </div>
        </div>

        <!-- Info produk -->
        <div class="col-lg-7">
            <!-- Judul dan harga -->
            <h2 class="fw-bold mb-2">{{ $produk->nama }}</h2>
            <p class="text-muted">{{ $produk->deskripsi }}</p>
            <h4 class="text-primary fw-semibold mb-4">
                Rp {{ number_format($produk->harga, 0, ',', '.') }}
            </h4>

            <!-- Form -->
            <form action="{{ route('users.cart.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                <!-- 1. Pilih Warna -->
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-light fw-semibold">1. Pilih Warna</div>
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach ($produk->varian->pluck('warna')->unique() as $warna)
                                <label class="color-option">
                                    <input type="radio" name="warna" value="{{ $warna }}" required hidden>
                                    <span class="rounded-circle border d-inline-block shadow-sm"
                                        style="width:35px; height:35px; background-color: {{ $warna }}; cursor: pointer;">
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 2. Pilih Ukuran -->
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-light fw-semibold">2. Pilih Ukuran</div>
                    <div class="card-body">
                        <select name="ukuran" class="form-select" required>
                            <option value="">-- Pilih Ukuran --</option>
                            @foreach ($produk->varian->pluck('ukuran')->unique() as $ukuran)
                                <option value="{{ $ukuran }}">{{ $ukuran }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 3. Pilih Lengan -->
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-light fw-semibold">3. Pilih Jenis Lengan</div>
                    <div class="card-body">
                        <select name="lengan" class="form-select" required>
                            <option value="">-- Pilih Jenis Lengan --</option>
                            @foreach ($produk->varian->pluck('lengan')->unique() as $lengan)
                                <option value="{{ $lengan }}">{{ ucfirst($lengan) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 4. Bahan -->
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-light fw-semibold">4. Pilih Bahan</div>
                    <div class="card-body">
                        <select name="material_id" class="form-select" required>
                            <option value="">-- Pilih Bahan --</option>
                            @foreach ($produk->varian->whereNotNull('material_id')->pluck('material_id')->unique() as $materialId)
                                <option value="{{ $materialId }}">
                                    {{ $produk->varian->firstWhere('material_id', $materialId)?->material?->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 5. Sablon -->
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-light fw-semibold">5. Pilihan Sablon</div>
                    <div class="card-body">
                        <div class="form-check form-check-inline">
                            <input type="radio" id="noSablon" name="pakai_sablon" value="0" checked class="form-check-input">
                            <label class="form-check-label" for="noSablon">Tidak</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="yesSablon" name="pakai_sablon" value="1" class="form-check-input">
                            <label class="form-check-label" for="yesSablon">Ya</label>
                        </div>

                        <div id="sablon-options" class="d-none mt-3 border-top pt-3">
                            <!-- Posisi -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Posisi Sablon</label>
                                <select name="sablon_posisi" class="form-select">
                                    <option value="">-- Pilih Posisi --</option>
                                    @foreach ($posisiSablon as $posisi)
                                        <option value="{{ $posisi->kode }}">{{ $posisi->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Upload gambar sablon -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Upload Desain Sablon</label>
                                <input type="file" name="sablon_gambar"
                                       class="form-control"
                                       accept="image/*">
                                <small class="text-muted">Format: JPG, PNG (max 2MB)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. Jumlah -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-light fw-semibold">6. Jumlah Pesanan</div>
                    <div class="card-body">
                        <input type="number" name="qty" value="1" min="1"
                               class="form-control" style="max-width: 150px;">
                    </div>
                </div>

                <!-- Tombol -->
                <button type="submit" class="btn btn-primary btn-lg px-4 py-2 w-100">
                    <i class="fas fa-shopping-cart me-2"></i> Tambah ke Keranjang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
jQuery(document).ready(function($) {
    // toggle sablon section
    $('input[name="pakai_sablon"]').on('change', function () {
        if ($(this).val() == "1") {
            $('#sablon-options').slideDown().removeClass('d-none');
        } else {
            $('#sablon-options').slideUp(function(){
                $(this).addClass('d-none');
                $('select[name="sablon_posisi"]').val('');
                $('input[name="sablon_gambar"]').val('');
                $('#preview-sablon').attr('src','').addClass('d-none');
            });
        }
    });

    // preview gambar sablon
    $('input[name="sablon_gambar"]').on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                $('#preview-sablon').attr('src', evt.target.result).removeClass('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

    // atur posisi sablon
    $('select[name="sablon_posisi"]').on('change', function () {
        const posisi = $(this).val();
        const $preview = $('#preview-sablon');

        if (!posisi) {
            $preview.addClass('d-none');
            return;
        }

        $preview.removeClass('d-none').css({
            top: '', left: '', right: '', bottom: '', transform: ''
        });

        switch(posisi) {
            case 'depan':
                $preview.css({ top: '30%', left: '50%', transform: 'translate(-50%, -50%)' });
                break;
            case 'belakang':
                $preview.css({ top: '30%', left: '50%', transform: 'translate(-50%, -50%)' });
                break;
            case 'lengan_kiri':
                $preview.css({ top: '40%', left: '15%', transform: 'translate(-50%, -50%)' });
                break;
            case 'lengan_kanan':
                $preview.css({ top: '40%', right: '15%', transform: 'translate(50%, -50%)' });
                break;
            default:
                $preview.css({ top: '30%', left: '50%', transform: 'translate(-50%, -50%)' });
        }
    });
});
</script>
@endpush
