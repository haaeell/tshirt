@extends('layouts.homepage')

@section('title', 'Keranjang - Toko Delapan')

@section('content')
    <div class="container my-5">
        <h2 class="fw-bold mb-4 text-center">🛒 Keranjang Belanja</h2>

        @if ($items->isEmpty())
            <div class="alert alert-info text-center shadow-sm">
                Keranjang masih kosong.
                <a href="{{ route('users.produk.index') }}" class="fw-semibold text-decoration-none">Belanja sekarang</a>.
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Varian</th>
                                    <th class="text-center" width="120">Harga</th>
                                    <th class="text-center" width="140">Qty</th>
                                    <th class="text-center" width="140">Subtotal</th>
                                    <th class="text-center" width="80">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="fw-semibold">{{ $item->produk->nama }}</td>
                                        <td>
                                            @if ($item->produkVarian)
                                                {{-- Lingkaran warna --}}
                                                <span class="d-inline-block rounded-circle border shadow-sm me-1"
                                                    style="width:18px; height:18px; background-color: {{ $item->produkVarian->warna }};">
                                                </span>
                                                <span class="badge bg-light text-dark">Ukuran:
                                                    {{ strtoupper($item->produkVarian->ukuran) }}</span>
                                                <span class="badge bg-light text-dark">Lengan:
                                                    {{ ucfirst($item->produkVarian->lengan) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif

                                        </td>
                                        <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('users.cart.update', $item->id) }}" method="POST"
                                                class="d-flex justify-content-center align-items-center gap-1">
                                                @csrf @method('PUT')
                                                <input type="number" name="qty" value="{{ $item->qty }}"
                                                    min="1" class="form-control form-control-sm text-center"
                                                    style="width:65px;">
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    title="Update">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end fw-semibold">Rp
                                            {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('users.cart.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus item ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total</th>
                                    <th class="text-end text-primary fs-5 fw-bold">
                                        Rp {{ number_format($items->sum('subtotal'), 0, ',', '.') }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tombol Checkout -->
            <div class="text-end mt-4">
                <a href="{{ route('users.checkout') }}" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm">
                    <i class="fas fa-credit-card me-1"></i> Lanjut ke Checkout
                </a>
            </div>
        @endif
    </div>
@endsection
