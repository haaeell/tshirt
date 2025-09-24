<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\KeranjangItem;
use App\Models\Produk;
use App\Models\ProdukVarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        // Log semua request yang masuk
        Log::info('=== Request masuk ke store keranjang ===', $request->all());

        $request->validate([
            'produk_id' => 'required', // cek nama tabel sesuai migrasi
            'qty' => 'required|integer|min:1',
            'warna' => 'required|string',
            'ukuran' => 'required|string',
            'lengan' => 'required|string',
            'material_id' => 'nullable|exists:materials,id',
        ]);

        Log::info('Validasi sukses', $request->only(['produk_id', 'qty', 'warna', 'ukuran', 'lengan', 'material_id']));

        $keranjang = Keranjang::firstOrCreate(['user_id' => Auth::id()]);
        Log::info('Keranjang user', ['keranjang_id' => $keranjang->id]);

        $produk = Produk::findOrFail($request->produk_id);
        Log::info('Produk ditemukan', ['produk_id' => $produk->id, 'nama' => $produk->nama]);

        // cari varian
        $varian = ProdukVarian::where('produk_id', $produk->id)
            ->where('warna', $request->warna)
            ->where('ukuran', $request->ukuran)
            ->where('lengan', $request->lengan)
            ->when($request->material_id, fn($q) => $q->where('material_id', $request->material_id))
            ->first();

        if (!$varian) {
            Log::warning('Varian tidak ditemukan', $request->only(['warna', 'ukuran', 'lengan', 'material_id']));
            return back()->with('error', 'Varian produk tidak ditemukan');
        }

        Log::info('Varian ditemukan', ['varian_id' => $varian->id]);

        // harga
        $harga = $varian->harga ?? $produk->harga;
        Log::info('Harga terpakai', ['harga' => $harga]);

        // cek item sudah ada?
        $item = $keranjang->items()
            ->where('produk_id', $produk->id)
            ->where('produk_varian_id', $varian->id)
            ->first();

        if ($item) {
            $item->qty += $request->qty;
            $item->subtotal = $item->qty * $harga;
            $item->save();
            Log::info('Item sudah ada, update qty', ['item_id' => $item->id, 'qty' => $item->qty]);
        } else {
            $pakaiSablon = $request->pakai_sablon == 1;
            $detailSablon = null;

            if ($pakaiSablon) {
                $detailSablon = [
                    'posisi' => $request->sablon_posisi,
                    'gambar' => $request->hasFile('sablon_gambar')
                        ? $request->file('sablon_gambar')->store('sablon', 'public')
                        : null,
                ];
                Log::info('Detail sablon', $detailSablon);
            }

            $newItem = $keranjang->items()->create([
                'produk_id' => $produk->id,
                'produk_varian_id' => $varian->id,
                'qty' => $request->qty,
                'harga_satuan' => $harga,
                'subtotal' => $harga * $request->qty,
                'pakai_sablon' => $pakaiSablon,
                'detail_sablon' => $detailSablon ? json_encode($detailSablon) : null,
            ]);

            Log::info('Item baru ditambahkan', ['item_id' => $newItem->id]);
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
