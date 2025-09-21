<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukVarian;
use App\Models\Material;
use Illuminate\Http\Request;

class ProdukVarianController extends Controller
{
    public function index()
    {
        $varians = ProdukVarian::with(['produk', 'material'])->get();
        $produk = Produk::all();
        $materials = Material::all();

        return view('admin.produk_varian.index', compact('varians', 'produk', 'materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id'   => 'required|exists:produk,id',
            'sku'         => 'required|unique:produk_varian,sku',
            'warna'       => 'required|string|max:100',
            'ukuran'      => 'required|in:XS,S,M,L,XL,XXL,XXXL',
            'lengan'      => 'required|in:pendek,panjang',
            'material_id' => 'nullable|exists:materials,id',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'nullable|numeric|min:0',
        ]);

        ProdukVarian::create($request->all());

        return redirect()->back()->with('success', 'Varian berhasil ditambahkan');
    }

    public function update(Request $request, ProdukVarian $produk_varian)
    {
        $request->validate([
            'produk_id'   => 'required|exists:produk,id',
            'sku'         => 'required|unique:produk_varian,sku,' . $produk_varian->id,
            'warna'       => 'required|string|max:100',
            'ukuran'      => 'required|in:XS,S,M,L,XL,XXL,XXXL',
            'lengan'      => 'required|in:pendek,panjang',
            'material_id' => 'nullable|exists:materials,id',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'nullable|numeric|min:0',
        ]);

        $produk_varian->update($request->all());

        return redirect()->back()->with('success', 'Varian berhasil diperbarui');
    }

    public function destroy(ProdukVarian $produk_varian)
    {
        $produk_varian->delete();
        return response()->json(['success' => true]);
    }
}
