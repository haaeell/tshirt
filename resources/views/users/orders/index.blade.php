@extends('layouts.homepage')

@section('title', 'Pesanan Saya - Toko Delapan')

@section('content')
    <div class="container my-5">
        <h2 class="fw-bold mb-4 text-center text-primary animate-fade">
            📦 Pesanan Saya
        </h2>

        @if ($pesanan->isEmpty())
            <div class="text-center py-5 border rounded-4 bg-light shadow-sm animate-fade">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold mb-1">Belum ada pesanan</h5>
                <p class="text-muted mb-3">Ayo mulai belanja produk favoritmu sekarang!</p>
                <a href="{{ route('users.produk.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-shopping-bag me-1"></i> Belanja Sekarang
                </a>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4 animate-fade">
                <div class="card-header bg-light fw-semibold border-0 py-3 rounded-top-4">
                    <i class="fas fa-list me-2 text-primary"></i> Daftar Pesanan Kamu
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary small">
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
                                    @php
                                        $statusClass = match ($p->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'dibayar' => 'bg-primary',
                                            'diproses' => 'bg-info text-dark',
                                            'dikirim' => 'bg-secondary',
                                            'selesai' => 'bg-success',
                                            'batal' => 'bg-danger',
                                            default => 'bg-light text-dark',
                                        };
                                        $kode = $p->kode ?? '#ORD' . str_pad($p->id, 4, '0', STR_PAD_LEFT);
                                    @endphp

                                    <tr class="order-row">
                                        <td class="fw-semibold text-primary text-center">{{ $kode }}</td>
                                        <td><span class="badge {{ $statusClass }}">{{ ucfirst($p->status) }}</span></td>
                                        <td class="fw-bold text-primary">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                        <td class="text-muted small">{{ $p->created_at->translatedFormat('d M Y, H:i') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('users.orders.show', $p->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 border-top bg-light text-end small text-muted rounded-bottom-4">
                        Menampilkan <strong>{{ $pesanan->count() }}</strong> pesanan
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        /* ====== Animations ====== */
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

        /* ====== Table & Badge Styling ====== */
        table th,
        table td {
            vertical-align: middle !important;
        }

        table tr.order-row:hover {
            background-color: #f8faff !important;
            transition: 0.2s ease-in-out;
        }

        .badge {
            font-size: 0.78rem;
            padding: 6px 10px;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* ====== Card & Button ====== */
        .card-header {
            font-size: 1rem;
            letter-spacing: 0.3px;
        }

        .btn-outline-primary:hover {
            color: #fff;
            background-color: #0d6efd;
            transition: 0.25s;
        }
    </style>
@endsection
