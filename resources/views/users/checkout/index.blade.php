@extends('layouts.homepage')

@section('title', 'Checkout - Toko Delapan')

@section('content')
    <div class="container my-5">
        <h2 class="fw-bold mb-4 text-center">🛍️ Checkout</h2>

        <form action="{{ route('users.placeOrder') }}" method="POST">
            @csrf
            <div class="row g-4">
                <!-- Alamat -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light fw-semibold">Alamat Pengiriman</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small">Nama Penerima</label>
                                <input type="text" name="nama_penerima" class="form-control form-control-sm" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Telepon</label>
                                <input type="text" name="telepon" class="form-control form-control-sm" value="{{ Auth::user()->customer->no_hp }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control form-control-sm" rows="3" required></textarea>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" name="kota" class="form-control form-control-sm"
                                        placeholder="Kota" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="provinsi" class="form-control form-control-sm"
                                        placeholder="Provinsi" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="kode_pos" class="form-control form-control-sm"
                                        placeholder="Kode Pos" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light fw-semibold">Ringkasan Belanja</div>
                        <div class="card-body">
                            <ul class="list-group mb-3">
                                @foreach ($keranjang->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $item->produk->nama }}</strong>
                                            <small class="d-block text-muted">x {{ $item->qty }}</small>

                                            @if ($item->produkVarian)
                                                <div class="mt-1">
                                                    <span class="badge bg-secondary">{{ $item->produkVarian->warna }}</span>
                                                    <span class="badge bg-light text-dark">Ukuran:
                                                        {{ strtoupper($item->produkVarian->ukuran) }}</span>
                                                    <span class="badge bg-light text-dark">Lengan:
                                                        {{ ucfirst($item->produkVarian->lengan) }}</span>
                                                    @if ($item->produkVarian->material)
                                                        <span class="badge bg-light text-dark">Bahan:
                                                            {{ $item->produkVarian->material->nama }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <span class="badge bg-primary rounded-pill">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Input Voucher -->
                            <div class="input-group mb-3">
                                <input type="text" name="voucher_kode" class="form-control form-control-sm"
                                    placeholder="Masukkan kode voucher">
                                <button type="button" id="btnVoucher"
                                    class="btn btn-outline-secondary btn-sm">Gunakan</button>
                            </div>


                            <div class="d-flex justify-content-between small mb-2">
                                <span>Subtotal</span>
                                <strong>Rp {{ number_format($keranjang->items->sum('subtotal'), 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between small mb-2">
                                <span>Ongkir</span>
                                <strong>Rp 20.000</strong>
                            </div>
                            <div class="d-flex justify-content-between small mb-2 text-success">
                                <span>Diskon Voucher</span>
                                <strong>- Rp 0</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fs-5 fw-bold text-primary">
                                <span>Total</span>
                                <span>Rp {{ number_format($keranjang->items->sum('subtotal') + 20000, 0, ',', '.') }}</span>
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
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            const $voucherInput = $('input[name="voucher_kode"]');
            const $voucherBtn = $('#btnVoucher');
            const $diskonRow = $('#row-diskon');
            const $diskonValue = $('#diskon-value');
            const $totalValue = $('#total-value');

            let subtotal = {{ $keranjang->items->sum('subtotal') }};
            let ongkir = 20000;

            $voucherBtn.on('click', function() {
                let kode = $voucherInput.val().trim();
                if (!kode) {
                    alert('Masukkan kode voucher terlebih dahulu!');
                    return;
                }

                $.ajax({
                    url: "{{ route('users.checkVoucher') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode: kode,
                        subtotal: subtotal
                    },
                    success: function(res) {
                        if (res.success) {
                            let diskon = res.diskon;
                            let total = subtotal + ongkir - diskon;

                            $diskonRow.removeClass('d-none');
                            $diskonValue.text("- Rp " + diskon.toLocaleString('id-ID'));
                            $totalValue.text("Rp " + total.toLocaleString('id-ID'));

                            // inject hidden input biar ikut ke form
                            if (!$('input[name="voucher_kode_hidden"]').length) {
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
                            alert(res.message || 'Voucher tidak valid!');
                            $diskonRow.addClass('d-none');
                            $diskonValue.text("- Rp 0");
                            $totalValue.text("Rp " + (subtotal + ongkir).toLocaleString(
                                'id-ID'));
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat cek voucher.');
                    }
                });
            });
        });
    </script>
@endpush
