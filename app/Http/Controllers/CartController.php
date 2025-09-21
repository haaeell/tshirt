<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\KeranjangItem;
use App\Models\Produk;
use App\Models\ProdukVarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $keranjang = Keranjang::firstOrCreate(['user_id' => Auth::id()]);
        $items = $keranjang->items()->with('produk', 'produkVarian')->get();

        return view('users.cart.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'qty' => 'required|integer|min:1',
            'warna' => 'required|string',
            'ukuran' => 'required|string',
            'lengan' => 'required|string',
            'material_id' => 'nullable|exists:materials,id',
        ]);

        $keranjang = Keranjang::firstOrCreate(['user_id' => Auth::id()]);

        $produk = Produk::findOrFail($request->produk_id);

        // cari varian berdasarkan kombinasi
        $varian = ProdukVarian::where('produk_id', $produk->id)
            ->where('warna', $request->warna)
            ->where('ukuran', $request->ukuran)
            ->where('lengan', $request->lengan)
            ->when($request->material_id, fn($q) => $q->where('material_id', $request->material_id))
            ->firstOrFail();

        // ambil harga varian kalau ada, kalau null pakai harga produk
        $harga = $varian->harga ?? $produk->harga;

        // cek apakah item sudah ada
        $item = $keranjang->items()
            ->where('produk_id', $produk->id)
            ->where('produk_varian_id', $varian->id)
            ->first();

        if ($item) {
            $item->qty += $request->qty;
            $item->subtotal = $item->qty * $harga;
            $item->save();
        } else {
            $detailSablon = null;
            $pakaiSablon = $request->pakai_sablon == 1;

            if ($pakaiSablon) {
                $detailSablon = [
                    'material_id' => $request->sablon_material_id,
                    'posisi' => $request->sablon_posisi ?? [],
                ];
            }

            $keranjang->items()->create([
                'produk_id' => $produk->id,
                'produk_varian_id' => $varian->id,
                'qty' => $request->qty,
                'harga_satuan' => $harga,
                'subtotal' => $harga * $request->qty,
                'pakai_sablon' => $pakaiSablon,
                'detail_sablon' => $detailSablon ? json_encode($detailSablon) : null,
            ]);
        }

        return redirect()->route('users.cart.index')
            ->with('success', 'Produk ditambahkan ke keranjang!');
    }


    public function update(Request $request, $id)
    {
        $request->validate(['qty' => 'required|integer|min:1']);

        $item = KeranjangItem::findOrFail($id);
        $item->qty = $request->qty;
        $item->subtotal = $item->qty * $item->harga_satuan;
        $item->save();

        return redirect()->route('users.cart.index');
    }

    // hapus item
    public function destroy($id)
    {
        $item = KeranjangItem::findOrFail($id);
        $item->delete();

        return redirect()->route('users.cart.index')->with('success', 'Item berhasil dihapus');
    }
}
