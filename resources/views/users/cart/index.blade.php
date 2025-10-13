@extends('layouts.homepage')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4 text-center"><i class="fas fa-shopping-cart me-2 text-primary"></i> Keranjang Belanja</h2>

    @if ($keranjang && $keranjang->items->count() > 0)
        <div class="table-responsive mb-4">
            <table class="table align-middle border">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Varian</th>
                        <th>Detail Ukuran</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($keranjang->items as $item)
                        <tr>
                            <!-- Produk -->
                            <td style="min-width: 180px;">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . ($item->produk->mockup->first()->file_path ?? 'placeholder.png')) }}"
                                         class="rounded" width="70" alt="">
                                    <div>
                                        <h6 class="fw-semibold mb-1">{{ $item->produk->nama }}</h6>
                                        <p class="text-muted small mb-0">{{ ucfirst($item->produk->jenis_produk) }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Varian -->
                            <td>
                                <ul class="list-unstyled small mb-0">
                                    @if($item->warna)<li><strong>Warna:</strong> {{ $item->warna }}</li>@endif
                                    @if($item->bahan)<li><strong>Bahan:</strong> {{ $item->bahan }}</li>@endif
                                    @if($item->lengan)<li><strong>Lengan:</strong> {{ ucfirst($item->lengan) }}</li>@endif
                                </ul>
                            </td>

                            <!-- Detail Ukuran -->
                            <td>
                                <table class="table table-sm border mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ukuran</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->details as $d)
                                            <tr>
                                                <td>{{ $d->ukuran }}</td>
                                                <td>{{ $d->qty }}</td>
                                                <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>

                            <!-- Subtotal -->
                            <td class="text-end fw-semibold text-primary">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>

                            <!-- Aksi -->
                            <td class="text-center">
                                <form action="{{ route('users.cart.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus item ini dari keranjang?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total -->
        @php
            $total = $keranjang->items->sum('subtotal');
        @endphp
        <div class="d-flex justify-content-between align-items-center border-top pt-3">
            <h5 class="fw-bold">Total Belanja:</h5>
            <h4 class="text-primary fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</h4>
        </div>

        <!-- Tombol -->
        <div class="d-flex justify-content-end gap-3 mt-4">
            <a href="{{ route('users.produk.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Lanjut Belanja
            </a>

            <a href="{{ route('users.checkout') }}" class="btn btn-success">
                <i class="fas fa-credit-card me-1"></i> Lanjut ke Checkout
            </a>
        </div>
    @else
        <div class="alert alert-light text-center p-5 border">
            <i class="fas fa-shopping-basket fa-2x mb-3 text-muted"></i>
            <h5 class="fw-bold">Keranjang kosong</h5>
            <p class="text-muted mb-3">Belum ada produk di keranjang kamu.</p>
            <a href="{{ route('users.produk.index') }}" class="btn btn-primary">
                <i class="fas fa-store me-1"></i> Belanja Sekarang
            </a>
        </div>
    @endif
</div>

<style>
    table td, table th { vertical-align: middle !important; }
</style>
@endsection
