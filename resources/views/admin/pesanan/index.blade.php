@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="container-fluid py-4">
    <h4 class="fw-bold mb-4">📦 Daftar Pesanan</h4>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanan as $p)
                            <tr>
                                <td class="fw-semibold text-primary">
                                    {{ $p->kode ?? '#ORD' . str_pad($p->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>{{ $p->user->nama ?? '-' }}</td>
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
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($p->status) }}</span>
                                </td>
                                <td class="fw-bold">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td class="text-muted small">{{ $p->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pesanan.show', $p->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
