@extends('layouts.homepage')

@section('title', 'Pesanan Saya - Toko Delapan')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4 text-center">📦 Pesanan Saya</h2>

    @if ($pesanan->isEmpty())
        <div class="alert alert-info text-center shadow-sm rounded-3 py-4">
            <p class="mb-1">Belum ada pesanan.</p>
            <a href="{{ route('users.produk.index') }}" class="btn btn-primary rounded-pill btn-sm mt-2">
                <i class="fas fa-shopping-bag me-1"></i> Belanja Sekarang
            </a>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Kode</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan as $p)
                                <tr>
                                    <td class="fw-semibold text-primary text-center">
                                        {{ $p->kode ?? '#ORD' . str_pad($p->id, 4, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <td>
                                        @php
                                            $statusClass = match($p->status) {
                                                'pending' => 'bg-warning text-dark',
                                                'dibayar' => 'bg-primary',
                                                'diproses' => 'bg-info text-dark',
                                                'dikirim' => 'bg-secondary',
                                                'selesai' => 'bg-success',
                                                'batal' => 'bg-danger',
                                                default => 'bg-light text-dark'
                                            };
                                        @endphp

                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>

                                    <td class="fw-bold text-primary">
                                        Rp {{ number_format($p->total, 0, ',', '.') }}
                                    </td>

                                    <td class="text-muted small">
                                        {{ $p->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('users.orders.show', $p->id) }}"
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-3 py-3 border-top text-end small text-muted">
                    Menampilkan {{ $pesanan->count() }} pesanan
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    table th, table td {
        vertical-align: middle !important;
    }
    .badge {
        font-size: 0.8rem;
        padding: 6px 10px;
        border-radius: 8px;
    }
</style>
@endsection
