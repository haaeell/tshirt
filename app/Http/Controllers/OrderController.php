<?php

namespace App\Http\Controllers;

use App\Models\AlamatPengiriman;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\PesananItemDetail;
use App\Models\UlasanProduk;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout()
    {
        $keranjang = Keranjang::with(['items.details', 'items.produk'])
            ->where('user_id', Auth::user()->id)
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

        $keranjang = Keranjang::with(['items.details'])->where('user_id', Auth::id(),)->first();

        if (!$keranjang) {
            return back()->with('error', 'Keranjang tidak ditemukan.');
        }

        $total = $keranjang->items->sum('subtotal');

        $pesanan = Pesanan::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'pending',
        ]);

        AlamatPengiriman::create([
            'pesanan_id' => $pesanan->id,
            'nama_penerima' => $request->nama_penerima,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
        ]);

        foreach ($keranjang->items as $item) {
            $pesananItem = PesananItem::create([
                'pesanan_id' => $pesanan->id,
                'produk_id' => $item->produk_id,
                'warna' => $item->warna,
                'bahan' => $item->bahan,
                'subtotal' => $item->subtotal,
                'custom_sablon_url' => $item->custom_sablon_url,
                'rincian_tambahan' => $item->rincian_tambahan,
            ]);

            foreach ($item->details as $d) {
                PesananItemDetail::create([
                    'pesanan_item_id' => $pesananItem->id,
                    'ukuran' => $d->ukuran,
                    'lengan' => $d->lengan,
                    'qty' => $d->qty,
                    'harga_satuan' => $d->harga_satuan,
                    'subtotal' => $d->subtotal,
                ]);
            }
        }

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

    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->latest()->get();
        return view('users.orders.index', compact('pesanan'));
    }

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

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = $request->status;
        $pesanan->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function reviewProduk(Request $request, $itemId)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            "rating.$itemId" => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $produkId = $request->produk_id;
        $rating = $request->input("rating.$itemId");
        $komentar = $request->komentar;

        $item = PesananItem::findOrFail($itemId);
        if ($item->pesanan->user_id !== $userId) {
            abort(403, 'Tidak diizinkan memberi ulasan untuk pesanan ini.');
        }

        $existing = UlasanProduk::where('pesanan_item_id', $itemId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            return back()->with('info', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        UlasanProduk::create([
            'pesanan_item_id' => $itemId,
            'produk_id' => $produkId,
            'user_id' => $userId,
            'rating' => $rating,
            'komentar' => $komentar,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda telah dikirim.');
    }
}
