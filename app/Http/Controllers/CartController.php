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
            'produk_varian_id' => 'nullable|exists:produk_varian,id'
        ]);

        $keranjang = Keranjang::firstOrCreate(['user_id' => Auth::id()]);

        $produk = Produk::findOrFail($request->produk_id);
        $harga = $produk->harga;

        if ($request->produk_varian_id) {
            $varian = ProdukVarian::findOrFail($request->produk_varian_id);
            $harga = $varian->harga ?? $produk->harga;
        }

        $item = $keranjang->items()
            ->where('produk_id', $produk->id)
            ->where('produk_varian_id', $request->produk_varian_id)
            ->first();

        if ($item) {
            $item->qty += $request->qty;
            $item->subtotal = $item->qty * $harga;
            $item->save();
        } else {
            $keranjang->items()->create([
                'produk_id' => $produk->id,
                'produk_varian_id' => $request->produk_varian_id,
                'qty' => $request->qty,
                'harga_satuan' => $harga,
                'subtotal' => $harga * $request->qty,
            ]);
        }

        return redirect()->route('users.cart.index')->with('success', 'Produk ditambahkan ke keranjang!');
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
