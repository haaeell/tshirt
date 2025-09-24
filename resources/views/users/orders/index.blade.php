@extends('layouts.homepage')

@section('title', 'Pesanan Saya - Toko Delapan')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4 text-center">📦 Pesanan Saya</h2>

    @if ($pesanan->isEmpty())
        <div class="alert alert-info text-center shadow-sm rounded-3">
            Belum ada pesanan. <a href="{{ route('users.produk.index') }}" class="alert-link">Belanja sekarang</a>.
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan as $p)
                                <tr>
                                    <td class="fw-semibold text-primary">{{ $p->kode }}</td>
                                    <td>
                                        <span class="badge
                                            @if($p->status === 'selesai') bg-success
                                            @elseif($p->status === 'batal') bg-danger
                                            @elseif($p->status === 'diproses') bg-info
                                            @elseif($p->status === 'menunggu_pembayaran') bg-warning text-dark
                                            @else bg-secondary @endif">
                                            {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                    <td class="text-muted small">{{ $p->created_at->format('d M Y, H:i') }}</td>
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
            </div>
        </div>
    @endif
</div>
@endsection
