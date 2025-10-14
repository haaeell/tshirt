<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        return view('laporan.penjualan');
    }

    public function data(Request $request)
    {
        $start = $request->get('start_date') ? Carbon::parse($request->start_date)->startOfDay() : now()->startOfMonth();
        $end   = $request->get('end_date') ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfMonth();

        // Pesanan selesai
        $pesanan = Pesanan::with('user')
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $pesanan->sum('total');

        // Produk terlaris
        $produkTerlaris = PesananItem::select('produk_id', DB::raw('SUM(subtotal) as total_penjualan'), DB::raw('COUNT(*) as jumlah_dipesan'))
            ->with('produk:id,nama')
            ->whereHas('pesanan', function ($q) use ($start, $end) {
                $q->where('status', 'selesai')->whereBetween('created_at', [$start, $end]);
            })
            ->groupBy('produk_id')
            ->orderByDesc('jumlah_dipesan')
            ->take(5)
            ->get();

        return response()->json([
            'data' => $pesanan,
            'total' => $total,
            'periode' => [
                'start' => $start->format('d M Y'),
                'end' => $end->format('d M Y')
            ],
            'produk_terlaris' => $produkTerlaris,
        ]);
    }
}
