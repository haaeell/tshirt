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
            'telepon'       => 'required|string|max:20',
            'alamat'        => 'required|string|max:500',
            'kota'          => 'required|string|max:100',
            'provinsi'      => 'required|string|max:100',
            'kode_pos'      => 'required|string|max:10',
        ]);

        $keranjang = Keranjang::where('user_id', Auth::id())
            ->with('items.produk', 'items.produkVarian.material')
            ->first();

        if (!$keranjang || $keranjang->items->isEmpty()) {
            return redirect()->route('users.cart.index')->with('warning', 'Keranjang kosong!');
        }

        $subtotal = $keranjang->items->sum('subtotal');
        $ongkir   = 20000; // dummy ongkir

        // === hitung diskon voucher ===
        $diskon = 0;
        $voucherKode = null;

        if ($request->filled('voucher_kode')) {
            $voucher = Voucher::where('kode', $request->voucher_kode)
                ->where('aktif', true)
                ->where(function($q) {
                    $q->whereNull('mulai')->orWhere('mulai', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('berakhir')->orWhere('berakhir', '>=', now());
                })
                ->first();

            if ($voucher && (!$voucher->min_belanja || $subtotal >= $voucher->min_belanja)) {
                $voucherKode = $voucher->kode;

                if ($voucher->tipe == 'persen') {
                    $diskon = floor(($voucher->nilai / 100) * $subtotal);
                    if ($voucher->maks_diskon) {
                        $diskon = min($diskon, $voucher->maks_diskon);
                    }
                } elseif ($voucher->tipe == 'nominal') {
                    $diskon = $voucher->nilai;
                }

                // update jumlah dipakai
                $voucher->increment('jumlah_dipakai');
            }
        }

        $total = max(0, $subtotal + $ongkir - $diskon);

        // === buat pesanan ===
        $pesanan = Pesanan::create([
            'kode'          => strtoupper(Str::random(8)),
            'user_id'       => Auth::id(),
            'status'        => 'menunggu_pembayaran',
            'nama_penerima' => $request->nama_penerima,
            'telepon'       => $request->telepon,
            'alamat'        => $request->alamat,
            'kota'          => $request->kota,
            'provinsi'      => $request->provinsi,
            'kode_pos'      => $request->kode_pos,
            'subtotal'      => $subtotal,
            'diskon'        => $diskon,
            'ongkir'        => $ongkir,
            'total'         => $total,
            'voucher_kode'  => $voucherKode,
            'voucher_nilai' => $diskon,
        ]);

        foreach ($keranjang->items as $item) {
            PesananItem::create([
                'pesanan_id'      => $pesanan->id,
                'produk_id'       => $item->produk_id,
                'produk_varian_id'=> $item->produk_varian_id,
                'nama_produk'     => $item->produk->nama,
                'warna'           => $item->produkVarian->warna ?? null,
                'ukuran'          => $item->produkVarian->ukuran ?? null,
                'lengan'          => $item->produkVarian->lengan ?? null,
                'bahan'           => $item->produkVarian->material->nama ?? null,
                'pakai_sablon'    => $item->pakai_sablon,
                'detail_sablon'   => $item->detail_sablon,
                'qty'             => $item->qty,
                'harga_satuan'    => $item->harga_satuan,
                'subtotal'        => $item->subtotal,
            ]);
        }

        // kosongkan keranjang
        $keranjang->items()->delete();

        return redirect()->route('users.orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    public function checkVoucher(Request $request)
    {
        $request->validate([
            'kode'     => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('kode', $request->kode)
            ->where('aktif', true)
            ->where(function($q) {
                $q->whereNull('mulai')->orWhere('mulai', '<=', now());
            })
            ->where(function($q) {
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

        $pesanan->update(['status' => 'menunggu_konfirmasi']);

        return back()->with('success', 'Bukti pembayaran berhasil diupload! Tunggu konfirmasi admin.');
    }
}
