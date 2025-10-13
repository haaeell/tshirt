<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\PosisiSablon;
use App\Models\Produk;
use App\Models\Ukuran;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $produk = Produk::get();
        return view('users.produk.index', compact('produk'));
    }

    public function show($id)
    {
        $produk = Produk::with(['warna', 'bahan', 'lengan', 'mockup'])->findOrFail($id);
        $ukuran = Ukuran::all();

        return view('users.produk.show', compact('produk', 'ukuran'));
    }

}
