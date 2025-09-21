@extends('layouts.homepage')

@section('title', 'Pesanan Saya - Toko Delapan')

@section('content')
    <h2 class="mb-4 text-center">Pesanan Saya</h2>

    @if ($pesanan->isEmpty())
        <div class="alert alert-info text-center">Belum ada pesanan.</div>
    @else
        <table class="table table-striped shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan as $p)
                    <tr>
                        <td>{{ $p->kode }}</td>
                        <td>
                            <span
                                class="badge bg-{{ $p->status == 'selesai' ? 'success' : ($p->status == 'batal' ? 'danger' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('users.orders.show', $p->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
