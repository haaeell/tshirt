<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\KeranjangItem;
use App\Models\KeranjangItemDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang user.
     */
    public function index()
    {
        $keranjang = Keranjang::with(['items.details', 'items.produk'])
            ->where('user_id', Auth::id())
            ->first();

        return view('users.cart.index', compact('keranjang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'detail_json' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $keranjang = Keranjang::firstOrCreate(['user_id' => $userId]);
            $produk = Produk::findOrFail($request->produk_id);
            $details = json_decode($request->detail_json, true);

            $subtotalDasar = collect($details)->sum('subtotal');

            $biayaTambahan = (float) ($request->biaya_tambahan_total ?? 0);
            $subtotalBaru = $subtotalDasar + $biayaTambahan;

            $item = KeranjangItem::where('keranjang_id', $keranjang->id)
                ->where('produk_id', $produk->id)
                ->where('warna', $request->warna)
                ->where('bahan', $request->bahan)
                ->first();

            if ($item) {
                $item->subtotal += $subtotalBaru;

                if ($request->filled('custom_sablon_data')) {
                    $item->custom_sablon_url = $request->custom_sablon_data;
                }

                if ($request->filled('rincian_tambahan')) {
                    $item->rincian_tambahan = $request->rincian_tambahan;
                }

                $item->save();
            } else {
                $item = KeranjangItem::create([
                    'keranjang_id' => $keranjang->id,
                    'produk_id' => $produk->id,
                    'warna' => $request->warna,
                    'bahan' => $request->bahan,
                    'subtotal' => $subtotalBaru,
                    'custom_sablon_url' => $request->custom_sablon_data,
                    'rincian_tambahan' => $request->rincian_tambahan ?? null,
                ]);
            }

            foreach ($details as $d) {
                $detail = KeranjangItemDetail::where('keranjang_item_id', $item->id)
                    ->where('ukuran', $d['ukuran'])
                    ->where('lengan', $d['lengan'])
                    ->first();

                if ($detail) {
                    $detail->qty += $d['qty'];
                    $detail->subtotal += $d['subtotal'];
                    $detail->save();
                } else {
                    KeranjangItemDetail::create([
                        'keranjang_item_id' => $item->id,
                        'ukuran' => $d['ukuran'],
                        'qty' => $d['qty'],
                        'harga_satuan' => $d['harga_satuan'],
                        'subtotal' => $d['subtotal'],
                        'lengan' => $d['lengan'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('users.cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambah ke keranjang: ' . $e->getMessage());
        }
    }


    /**
     * Update item di keranjang (jumlah atau varian).
     */
    public function update(Request $request, $id)
    {
        $item = KeranjangItem::whereHas('keranjang', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $item->update([
            'warna' => $request->warna,
            'bahan' => $request->bahan,
            'lengan' => $request->lengan,
        ]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    /**
     * Hapus item dari keranjang.
     */
    public function destroy($id)
    {
        $item = KeranjangItem::whereHas('keranjang', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $item->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
