@extends('layouts.homepage')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4 text-center text-primary animate-fade">
            <i class="fas fa-shopping-cart me-2"></i> Keranjang Belanja
        </h2>

        @if ($keranjang && $keranjang->items->count() > 0)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Varian</th>
                                    <th>Detail Ukuran</th>
                                    <th>Biaya Tambahan</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keranjang->items as $item)
                                    <tr class="cart-item-row">
                                        <!-- Produk -->
                                        <td style="min-width: 200px;">
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
                                            </div>
                                        </td>

                                        <!-- Varian -->
                                        <td>
                                            <ul class="list-unstyled small mb-0">
                                                @if ($item->warna)
                                                    <li><span class="fw-semibold">🎨 Warna:</span> {{ $item->warna }}</li>
                                                @endif
                                                @if ($item->bahan)
                                                    <li><span class="fw-semibold">🧵 Bahan:</span> {{ $item->bahan }}</li>
                                                @endif
                                            </ul>
                                        </td>

                                        <!-- Detail Ukuran -->
                                        <td>
                                            <div class="rounded border bg-light-subtle p-2">
                                                <table
                                                    class="table table-sm table-borderless mb-0 small align-middle text-center">
                                                    <thead class="fw-semibold text-secondary border-bottom">
                                                        <tr>
                                                            <th>Ukuran</th>
                                                            <th>Lengan</th>
                                                            <th>Qty</th>
                                                            <th>Harga</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($item->details as $d)
                                                            <tr>
                                                                <td>{{ strtoupper($d->ukuran) }}</td>
                                                                <td>{{ ucfirst($d->lengan) }}</td>
                                                                <td>{{ $d->qty }}</td>
                                                                <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                                                                </td>
                                                                <td class="text-primary fw-semibold">
                                                                    Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                        @php
                                            $tambahan = is_string($item->rincian_tambahan)
                                                ? json_decode($item->rincian_tambahan, true)
                                                : $item->rincian_tambahan;
                                        @endphp
                                        <td>
                                            @if ($tambahan)
                                                <div class="mt-3">
                                                    <h6 class="fw-semibold mb-2 text-secondary">🧾 Rincian Biaya Tambahan:
                                                    </h6>
                                                    <div class="p-2 border rounded bg-light-subtle">
                                                        <ul class="small mb-0">
                                                            @if (!empty($tambahan['bahan']['nama']))
                                                                <li>
                                                                    <strong>Bahan:</strong>
                                                                    {{ $tambahan['bahan']['nama'] }}
                                                                    (+Rp
                                                                    {{ number_format($tambahan['bahan']['total'] ?? 0, 0, ',', '.') }})
                                                                </li>
                                                            @endif

                                                            @if (!empty($tambahan['sablon']))
                                                                <li class="mt-1">
                                                                    <strong>Sablon:</strong>
                                                                    <ul class="mb-0 ps-3">
                                                                        @foreach ($tambahan['sablon'] as $s)
                                                                            <li>{{ $s['type'] }} {{ $s['sizeLabel'] }}
                                                                                (+Rp
                                                                                {{ number_format($s['cost'], 0, ',', '.') }})
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>



                                        <!-- Subtotal -->
                                        <td class="text-end fw-bold text-primary">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>

                                        <!-- Aksi -->
                                        <td class="text-center">
                                            <form action="{{ route('users.cart.destroy', $item->id) }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-light btn-sm text-danger border-0 rounded-circle delete-btn"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TOTAL -->
            @php $total = $keranjang->items->sum('subtotal'); @endphp
            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-light animate-fade">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Total Belanja:</h5>
                    <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="d-flex justify-content-end gap-3 mt-3">
                <a href="{{ route('users.produk.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Lanjut Belanja
                </a>

                <a href="{{ route('users.checkout') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="fas fa-credit-card me-1"></i> Checkout Sekarang
                </a>
            </div>
        @else
            <div class="text-center py-5 border rounded-4 bg-light-subtle shadow-sm animate-fade">
                <i class="fas fa-shopping-basket fa-3x mb-3 text-muted"></i>
                <h5 class="fw-bold mb-1">Keranjang kamu masih kosong</h5>
                <p class="text-muted mb-3">Ayo belanja produk keren sekarang!</p>
                <a href="{{ route('users.produk.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-store me-1"></i> Belanja Sekarang
                </a>
            </div>
        @endif
    </div>

    <style>
        /* ====== Animation ====== */
        .animate-fade {
            animation: fadeInUp 0.6s ease-in-out;
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

        /* ====== Table tweaks ====== */
        table td,
        table th {
            vertical-align: middle !important;
        }

        .cart-item-row:hover {
            background-color: #f9faff;
            transition: 0.3s;
        }

        /* ====== Delete button hover ====== */
        .delete-btn:hover {
            background-color: #f8d7da !important;
            color: #dc3545 !important;
            transform: scale(1.1);
            transition: 0.25s ease;
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Konfirmasi hapus pakai SweetAlert
                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.preventDefault();
                        const form = btn.closest('.delete-form');
                        Swal.fire({
                            title: 'Hapus produk ini?',
                            text: "Item akan dihapus dari keranjang kamu.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, hapus',
                            cancelButtonText: 'Batal'
                        }).then(result => {
                            if (result.isConfirmed) form.submit();
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
