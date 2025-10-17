<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UlasanProduk;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index(Request $request)
    {
        $query = UlasanProduk::with(['produk', 'user'])
            ->when($request->produk, fn($q) => $q->whereHas('produk', fn($p) => $p->where('nama', 'like', '%' . $request->produk . '%')))
            ->when($request->user, fn($q) => $q->whereHas('user', fn($u) => $u->where('nama', 'like', '%' . $request->user . '%')))
            ->when($request->rating, fn($q) => $q->where('rating', $request->rating))
            ->latest();

        $ulasan = $query->paginate(10);

        return view('admin.ulasan.index', compact('ulasan'));
    }
}
