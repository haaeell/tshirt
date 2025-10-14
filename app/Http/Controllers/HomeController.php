<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {
        $tahun = Carbon::now()->year;

        // === Statistik utama ===
        $data = [
            'totalProduk' => Produk::count(),
            'totalPesanan' => Pesanan::count(),
            'totalPendapatan' => Pesanan::where('status', 'selesai')->sum('total'),
            'totalCustomer' => User::where('role', 'customer')->count(),
            'pesananTerbaru' => Pesanan::with('user')->latest()->take(5)->get(),
        ];

        // === Data grafik penjualan 1 tahun penuh ===
        $rawData = DB::table('pesanan')
            ->selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
            ->where('status', 'selesai')
            ->whereYear('created_at', $tahun)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // isi default 12 bulan
        $labels = [];
        $values = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->translatedFormat('F');
            $values[] = $rawData[$i] ?? 0;
        }

        return view('home', compact('data', 'labels', 'values', 'tahun'));
    }

    public function welcome()
    {
        $produk = Produk::latest()
            ->take(8)
            ->get();

        return view('welcome', compact('produk'));
    }

    public function tentang()
    {
        return view('tentang');
    }

    public function kontak()
    {
        return view('kontak');
    }
}
