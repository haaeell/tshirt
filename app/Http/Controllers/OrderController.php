<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout()
    {
        $keranjang = \App\Models\Keranjang::with(['items.details', 'items.produk'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$keranjang || $keranjang->items->isEmpty()) {
            return redirect()->route('users.cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $total = $keranjang->items->sum('subtotal');

        return view('users.checkout.index', compact('keranjang', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        $keranjang = \App\Models\Keranjang::with(['items.details'])->where('user_id', auth()->id())->first();

        if (!$keranjang) {
            return back()->with('error', 'Keranjang tidak ditemukan.');
        }

        $total = $keranjang->items->sum('subtotal');

        // === Simpan Pesanan ===
        $pesanan = \App\Models\Pesanan::create([
            'user_id' => auth()->id(),
            'total' => $total,
            'status' => 'pending',
        ]);

        // Simpan Alamat
        \App\Models\AlamatPengiriman::create([
            'pesanan_id' => $pesanan->id,
            'nama_penerima' => $request->nama_penerima,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
        ]);

        // Simpan Item
        foreach ($keranjang->items as $item) {
            $pesananItem = \App\Models\PesananItem::create([
                'pesanan_id' => $pesanan->id,
                'produk_id' => $item->produk_id,
                'warna' => $item->warna,
                'bahan' => $item->bahan,
                'lengan' => $item->lengan,
                'subtotal' => $item->subtotal,
            ]);

            foreach ($item->details as $d) {
                \App\Models\PesananItemDetail::create([
                    'pesanan_item_id' => $pesananItem->id,
                    'ukuran' => $d->ukuran,
                    'qty' => $d->qty,
                    'harga_satuan' => $d->harga_satuan,
                    'subtotal' => $d->subtotal,
                ]);
            }
        }

        // Kosongkan keranjang setelah checkout
        $keranjang->items()->delete();
        $keranjang->delete();

        return redirect()->route('users.orders.index')->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }


    public function checkVoucher(Request $request)
    {
        $request->validate([
            'kode' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('kode', $request->kode)
            ->where('aktif', true)
            ->where(function ($q) {
                $q->whereNull('mulai')->orWhere('mulai', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('berakhir')->orWhere('berakhir', '>=', now());
            })
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak valid']);
        }

        if ($voucher->min_belanja && $request->subtotal < $voucher->min_belanja) {
            return response()->json(['success' => false, 'message' => 'Minimal belanja Rp ' . number_format($voucher->min_belanja, 0, ',', '.')]);
        }

        $diskon = 0;
        if ($voucher->tipe == 'persen') {
            $diskon = floor(($voucher->nilai / 100) * $request->subtotal);
            if ($voucher->maks_diskon) {
                $diskon = min($diskon, $voucher->maks_diskon);
            }
        } elseif ($voucher->tipe == 'nominal') {
            $diskon = $voucher->nilai;
        }

        return response()->json(['success' => true, 'diskon' => $diskon]);
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
        $pesanan = Pesanan::with([
            'items.details',
            'alamatPengiriman'
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('users.orders.show', compact('pesanan'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $pesanan = Pesanan::where('user_id', Auth::id())->findOrFail($id);

        $path = $request->file('bukti')->store('bukti_pembayaran', 'public');
        $pesanan->update(['bukti_pembayaran' => $path, 'status' => 'dibayar']);

        return back()->with('success', 'Bukti pembayaran berhasil diupload.');
    }

}
