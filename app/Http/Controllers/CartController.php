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

            $totalQty = collect($details)->sum('qty');

            $rincianTambahan = json_decode($request->rincian_tambahan ?? '{}', true);

            $bahanTotal = (int) ($rincianTambahan['bahan']['total'] ?? 0);

            $sablonFinal = [];
            $sablonTotal = 0;

            if (!empty($rincianTambahan['sablon'])) {
                foreach ($rincianTambahan['sablon'] as $s) {
                    $cost = (int) ($s['cost'] ?? 0);
                    $subtotal = $cost * $totalQty;
                    $sablonTotal += $subtotal;

                    $sablonFinal[] = [
                        'type' => $s['type'] ?? null,
                        'sizeLabel' => $s['sizeLabel'] ?? null,
                        'cost' => $cost,
                        'qty' => $totalQty,
                        'subtotal' => $subtotal,
                    ];
                }
            }

            $rincianTambahanFinal = [
                'qty_total' => $totalQty,
                'total_tambahan' => $bahanTotal + $sablonTotal,
                'bahan' => $rincianTambahan['bahan'] ?? null,
                'sablon' => $sablonFinal,
            ];

            $item = KeranjangItem::where('keranjang_id', $keranjang->id)
                ->where('produk_id', $produk->id)
                ->where('warna', $request->warna)
                ->where('bahan', $request->bahan)
                ->first();

            if ($item) {
                $item->subtotal += $subtotalBaru;
                $item->custom_sablon_url = $request->custom_sablon_data ?? $item->custom_sablon_url;
                $item->rincian_tambahan = json_encode($rincianTambahanFinal);
                $item->save();
            } else {
                $item = KeranjangItem::create([
                    'keranjang_id' => $keranjang->id,
                    'produk_id' => $produk->id,
                    'warna' => $request->warna,
                    'bahan' => $request->bahan,
                    'subtotal' => $subtotalBaru,
                    'custom_sablon_url' => $request->custom_sablon_data,
                    'rincian_tambahan' => json_encode($rincianTambahanFinal),
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
            return back()->with('error', $e->getMessage());
        }
    }

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

    public function destroy($id)
    {
        $item = KeranjangItem::whereHas('keranjang', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $item->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
