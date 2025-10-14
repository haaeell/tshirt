@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            background: #f5f7fb;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #007bff 0%, #00c6ff 100%);
            color: #fff;
            border-radius: 12px;
            padding: 30px 40px;
            margin-bottom: 30px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            border: none;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .stats-icon {
            font-size: 2rem;
            color: #007bff;
            background: rgba(0, 123, 255, 0.1);
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .chart-card,
        .card-activity,
        .card-table {
            border: none;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .activity-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="dashboard-header">
            <h3 class="fw-bold mb-1">Selamat Datang, Admin 👋</h3>
            <p class="mb-0">Ringkasan penjualan dan aktivitas terkini toko Anda</p>
        </div>

        <div class="row g-4 mb-4 text-center">
            <div class="col-md-3">
                <div class="card stats-card py-4 border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="stats-icon mb-3 mx-auto">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h6 class="text-muted mb-1">Total Produk</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['totalProduk'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card py-4 border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="stats-icon mb-3 mx-auto bg-success bg-opacity-10 text-success">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <h6 class="text-muted mb-1">Total Pesanan</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['totalPesanan'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card py-4 border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="stats-icon mb-3 mx-auto bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h6 class="text-muted mb-1">Pendapatan</h6>
                        <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($data['totalPendapatan'], 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card py-4 border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="stats-icon mb-3 mx-auto bg-info bg-opacity-10 text-info">
                            <i class="bi bi-people"></i>
                        </div>
                        <h6 class="text-muted mb-1">Customer</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['totalCustomer'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHART -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="chart-card p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow me-2"></i>Penjualan Tahun {{ $tahun }}
                    </h5>
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-activity p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h5>
                    @forelse ($data['pesananTerbaru'] as $p)
                        <div class="activity-item d-flex justify-content-between align-items-start">
                            <div>
                                <i class="bi bi-person-circle text-primary me-1"></i>
                                <strong>{{ $p->user->nama ?? 'Customer' }}</strong><br>
                                <small class="text-muted">{{ ucfirst($p->status) }} • Rp
                                    {{ number_format($p->total, 0, ',', '.') }}</small>
                            </div>
                            <small class="text-muted">{{ $p->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">Belum ada aktivitas terbaru</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TABEL PESANAN -->
        <div class="card card-table">
            <div class="card-header bg-white px-4 py-3 fw-bold">
                <i class="bi bi-bag-check me-2 text-primary"></i>Pesanan Terbaru
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data['pesananTerbaru'] as $p)
                            <tr>
                                <td>#{{ $p->id }}</td>
                                <td>{{ $p->user->nama ?? '-' }}</td>
                                <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ match ($p->status) {
                                            'pending' => 'warning',
                                            'dibayar' => 'info',
                                            'diproses' => 'primary',
                                            'dikirim' => 'secondary',
                                            'selesai' => 'success',
                                            'batal' => 'danger',
                                            default => 'light',
                                        } }}">{{ ucfirst($p->status) }}</span>
                                </td>
                                <td>{{ $p->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: @json($values),
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: '#007bff',
                    borderWidth: 2,
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    </script>
@endsection
