<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\PesananItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // form checkout
    public function checkout()
    {
        $keranjang = Keranjang::where('user_id', Auth::id())->with('items.produk', 'items.produkVarian')->first();

        if (!$keranjang || $keranjang->items->isEmpty()) {
            return redirect()->route('users.cart.index')->with('warning', 'Keranjang kosong!');
        }

        return view('users.checkout.index', compact('keranjang'));
    }

    // simpan pesanan
    public function placeOrder(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
        ]);

        $keranjang = Keranjang::where('user_id', Auth::id())->with('items.produk', 'items.produkVarian')->first();

        if (!$keranjang || $keranjang->items->isEmpty()) {
            return redirect()->route('users.cart.index')->with('warning', 'Keranjang kosong!');
        }

        $subtotal = $keranjang->items->sum('subtotal');
        $ongkir = 20000; // dummy
        $total = $subtotal + $ongkir;

        $pesanan = Pesanan::create([
            'kode' => strtoupper(Str::random(8)),
            'user_id' => Auth::id(),
            'status' => 'menunggu_pembayaran',
            'nama_penerima' => $request->nama_penerima,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'subtotal' => $subtotal,
            'diskon' => 0,
            'ongkir' => $ongkir,
            'total' => $total,
        ]);

        foreach ($keranjang->items as $item) {
            PesananItem::create([
                'pesanan_id' => $pesanan->id,
                'produk_id' => $item->produk_id,
                'produk_varian_id' => $item->produk_varian_id,
                'nama_produk' => $item->produk->nama,
                'warna' => $item->produkVarian->warna ?? null,
                'ukuran' => $item->produkVarian->ukuran ?? null,
                'lengan' => $item->produkVarian->lengan ?? null,
                'bahan' => $item->produkVarian->material->nama ?? null,
                'qty' => $item->qty,
                'harga_satuan' => $item->harga_satuan,
                'subtotal' => $item->subtotal,
            ]);
        }

        // kosongkan keranjang
        $keranjang->items()->delete();

        return redirect()->route('users.orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    // daftar pesanan user
    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->latest()->get();
        return view('users.orders.index', compact('pesanan'));
    }

    // detail pesanan
    public function show($id)
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->with('items')->findOrFail($id);
        return view('users.orders.show', compact('pesanan'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pesanan = Pesanan::where('user_id', Auth::id())->findOrFail($id);

        // simpan file ke storage
        $path = $request->file('bukti')->store('bukti_pembayaran', 'public');

        // update atau buat pembayaran
        $pesanan->pembayaran()->updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'metode' => 'transfer',
                'jumlah' => $pesanan->total,
                'status' => 'pending',
                'bukti' => $path,
            ]
        );

        return back()->with('success', 'Bukti pembayaran berhasil diupload! Tunggu konfirmasi admin.');
    }
}
