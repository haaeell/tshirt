<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $produk = Produk::where('aktif', true)->get();
        return view('users.produk.index', compact('produk'));
    }

    public function show($id)
    {
        $produk = Produk::with('varian')->findOrFail($id);
        return view('users.produk.show', compact('produk'));
    }
}
