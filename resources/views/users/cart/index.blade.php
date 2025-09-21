@extends('layouts.homepage')

@section('title', 'Keranjang - Toko Delapan')

@section('content')
    <h2 class="mb-4 text-center">Keranjang Belanja</h2>

    @if ($items->isEmpty())
        <div class="alert alert-info text-center">
            Keranjang masih kosong. <a href="{{ route('users.produk.index') }}">Belanja sekarang</a>.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Varian</th>
                        <th class="text-center" width="120">Harga</th>
                        <th class="text-center" width="120">Qty</th>
                        <th class="text-center" width="120">Subtotal</th>
                        <th class="text-center" width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td><strong>{{ $item->produk->nama }}</strong></td>
                            <td>
                                @if ($item->produkVarian)
                                    {{ $item->produkVarian->warna }} |
                                    Ukuran: {{ $item->produkVarian->ukuran }} |
                                    Lengan: {{ $item->produkVarian->lengan }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <form action="{{ route('users.cart.update', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="number" name="qty" value="{{ $item->qty }}" min="1"
                                        class="form-control form-control-sm text-center d-inline-block" style="width:60px;">
                                    <button type="submit" class="btn btn-sm btn-success mt-1">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <form action="{{ route('users.cart.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus item ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
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
                        <th class="text-end">Rp {{ number_format($items->sum('subtotal'), 0, ',', '.') }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-end mt-3">
            <a href="{{ route('users.checkout') }}" class="btn btn-primary">
                <i class="fas fa-credit-card"></i> Lanjut ke Checkout
            </a>
        </div>
    @endif
@endsection
