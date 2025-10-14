@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            background: #f5f7fb;
        }

        .filter-card,
        .card-modern {
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .table-modern th {
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #6c757d;
            letter-spacing: 0.5px;
        }

        .top-products-list li {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding: 8px 0;
        }

        .top-products-list li:last-child {
            border-bottom: none;
        }

        .chart-card canvas {
            max-height: 280px;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-graph-up me-2 text-primary"></i>Laporan Penjualan</h4>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <!-- FILTER -->
        <div class="card filter-card p-4 mb-4">
            <form id="filterForm" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" class="form-control" name="start_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="end_date">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Tampilkan</button>
                </div>
                <div class="col-md-3 text-end">
                    <button type="button" class="btn btn-success w-100"><i class="bi bi-file-earmark-excel"></i> Export
                        Excel</button>
                </div>
            </form>
        </div>

        <!-- HASIL -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-modern">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Hasil Penjualan</h6>
                        <small class="text-muted" id="periodeLabel"></small>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle table-modern mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="laporanBody">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Silakan pilih rentang tanggal
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light text-end fw-bold">
                        Total Pendapatan: <span id="totalPenjualan" class="text-primary">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- PRODUK TERLARIS -->
            <div class="col-lg-4">
                <div class="card card-modern chart-card p-3">
                    <h6 class="fw-bold mb-3"><i class="bi bi-fire text-danger me-1"></i> Produk Terlaris</h6>
                    <ul class="top-products-list list-unstyled mb-3" id="topProductsList">
                        <li class="text-center text-muted">Belum ada data</li>
                    </ul>
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chart;

        document.getElementById('filterForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const start = this.start_date.value;
            const end = this.end_date.value;

            const res = await fetch(
            `{{ route('laporan.penjualan.data') }}?start_date=${start}&end_date=${end}`);
            const data = await res.json();

            // === Tabel ===
            let rows = '';
            data.data.forEach(p => {
                rows += `
            <tr>
                <td>#${p.id}</td>
                <td>${p.user?.nama ?? '-'}</td>
                <td>${new Date(p.created_at).toLocaleDateString('id-ID')}</td>
                <td>Rp ${Number(p.total).toLocaleString('id-ID')}</td>
                <td><span class="badge bg-success">Selesai</span></td>
            </tr>`;
            });
            if (!rows) rows =
            `<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data</td></tr>`;
            document.getElementById('laporanBody').innerHTML = rows;

            // === Label Periode & Total ===
            document.getElementById('periodeLabel').innerText =
                `Periode: ${data.periode.start} - ${data.periode.end}`;
            document.getElementById('totalPenjualan').innerText = 'Rp ' + data.total.toLocaleString('id-ID');

            // === Produk Terlaris ===
            const topList = document.getElementById('topProductsList');
            topList.innerHTML = '';
            if (data.produk_terlaris.length === 0) {
                topList.innerHTML = `<li class="text-center text-muted">Tidak ada data</li>`;
            } else {
                data.produk_terlaris.forEach(item => {
                    topList.innerHTML += `
                <li>
                    <span>${item.produk?.nama ?? '-'}</span>
                    <strong>${item.jumlah_dipesan}x</strong>
                </li>`;
                });
            }

            // === Chart Produk Terlaris ===
            const labels = data.produk_terlaris.map(p => p.produk?.nama);
            const values = data.produk_terlaris.map(p => p.jumlah_dipesan);

            const ctx = document.getElementById('topProductsChart');
            if (chart) chart.destroy();
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Jumlah Terjual',
                        data: values,
                        backgroundColor: [
                            '#007bff', '#28a745', '#ffc107', '#17a2b8', '#dc3545'
                        ],
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
