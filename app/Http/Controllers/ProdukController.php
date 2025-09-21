<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        return view('admin.produk.index', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'aktif' => 'boolean',
        ]);

        Produk::create($request->only('nama', 'jenis', 'deskripsi', 'harga', 'aktif'));

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'aktif' => 'boolean',
        ]);

        $produk->update($request->only('nama', 'jenis', 'deskripsi', 'harga', 'aktif'));

        return redirect()->back()->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return response()->json(['success' => true]);
    }
}
