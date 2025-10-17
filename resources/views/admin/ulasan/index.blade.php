@extends('layouts.app')
@section('title', 'Ulasan Produk')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0"><i class="fas fa-star text-warning me-2"></i>Ulasan Produk</h4>
            <form class="d-flex gap-2" method="GET">
                <input type="text" name="produk" value="{{ request('produk') }}" class="form-control form-control-sm"
                    placeholder="Cari produk...">
                <input type="text" name="user" value="{{ request('user') }}" class="form-control form-control-sm"
                    placeholder="Cari pelanggan...">
                <select name="rating" class="form-select form-select-sm">
                    <option value="">Semua Rating</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} ★</option>
                    @endfor
                </select>
                <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Pelanggan</th>
                                <th>Rating</th>
                                <th>Komentar</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ulasan as $u)
                                <tr>
                                    <td class="fw-semibold">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . ($u->produk->mockup->first()->file_path ?? 'placeholder.png')) }}"
                                                class="rounded me-2" style="width:45px;height:45px;object-fit:cover;">
                                            <div>
                                                {{ $u->produk->nama }}
                                                <div class="text-muted small">
                                                    {{ strtoupper($u->produk->jenis_produk ?? '-') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $u->user->nama ?? '-' }}</td>
                                    <td>
                                        <div class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $u->rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td>{{ $u->komentar ?: '-' }}</td>
                                    <td>{{ $u->created_at->translatedFormat('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle me-1"></i> Belum ada ulasan produk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($ulasan->hasPages())
                <div class="card-footer border-0 bg-light py-2">
                    <div class="d-flex justify-content-center">
                        {{ $ulasan->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .table td,
        .table th {
            vertical-align: middle !important;
        }

        .fa-star,
        .fa-star-o {
            font-size: 1rem;
        }
    </style>
@endsection
