<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Ukuran;
use App\Models\Warna;
use App\Models\Bahan;
use App\Models\Lengan;
use App\Models\Sablon;
use App\Models\Mockup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukVarianController extends Controller
{
    public function index(Request $request)
    {
        $produk = Produk::with(['warna', 'bahan', 'lengan', 'mockup'])->get();

        $selectedId = $request->input('produk_id') ?? $produk->first()?->id;

        $selectedProduk = Produk::with(['warna', 'bahan', 'lengan', 'mockup'])
            ->find($selectedId);

        return view('admin.produk.varian', compact('produk', 'selectedProduk'));
    }


    // === WARNA ===
    public function storeWarna(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'nama' => 'required|string|max:100',
            'hex' => 'nullable|string|max:10',
        ]);

        Warna::create($request->only('produk_id', 'nama', 'hex'));
        return back()->with('success', 'Warna berhasil ditambahkan');
    }

    public function updateWarna(Request $request, Warna $warna)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'hex' => 'nullable|string|max:10',
        ]);

        $warna->update($request->only('nama', 'hex'));
        return back()->with('success', 'Warna berhasil diperbarui');
    }

    public function destroyWarna(Warna $warna)
    {
        $warna->delete();
        return back()->with('success', 'Warna berhasil dihapus');
    }

    // === BAHAN ===
    public function storeBahan(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'nama' => 'required|string|max:100',
            'tambahan_harga' => 'nullable|numeric|min:0',
        ]);

        Bahan::create([
            'produk_id' => $request->produk_id,
            'nama' => $request->nama,
            'tambahan_harga' => $request->tambahan_harga ?? 0,
        ]);
        return back()->with('success', 'Bahan berhasil ditambahkan');
    }

    public function updateBahan(Request $request, Bahan $bahan)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'tambahan_harga' => 'nullable|numeric|min:0',
        ]);

        $bahan->update($request->only('nama', 'tambahan_harga'));
        return back()->with('success', 'Bahan berhasil diperbarui');
    }

    public function destroyBahan(Bahan $bahan)
    {
        $bahan->delete();
        return back()->with('success', 'Bahan berhasil dihapus');
    }

    // === LENGAN ===
    public function storeLengan(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'tipe' => 'required|string|max:100',
            'tambahan_harga' => 'nullable|numeric|min:0',
        ]);

        Lengan::create($request->only('produk_id', 'tipe', 'tambahan_harga'));
        return back()->with('success', 'Lengan berhasil ditambahkan');
    }

    public function updateLengan(Request $request, Lengan $lengan)
    {
        $request->validate([
            'tipe' => 'required|string|max:100',
            'tambahan_harga' => 'nullable|numeric|min:0',
        ]);

        $lengan->update($request->only('tipe', 'tambahan_harga'));
        return back()->with('success', 'Lengan berhasil diperbarui');
    }

    public function destroyLengan(Lengan $lengan)
    {
        $lengan->delete();
        return back()->with('success', 'Lengan berhasil dihapus');
    }

    // === SABLON ===
    public function storeSablon(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:100',
            'ukuran' => 'required|string|max:100',
            'tambahan_harga' => 'nullable|numeric|min:0',
        ]);

        Sablon::create($request->only('jenis', 'ukuran', 'tambahan_harga'));
        return back()->with('success', 'Sablon berhasil ditambahkan');
    }

    public function updateSablon(Request $request, Sablon $sablon)
    {
        $request->validate([
            'jenis' => 'required|string|max:100',
            'ukuran' => 'required|string|max:100',
            'tambahan_harga' => 'nullable|numeric|min:0',
        ]);

        $sablon->update($request->only('jenis', 'ukuran', 'tambahan_harga'));
        return back()->with('success', 'Sablon berhasil diperbarui');
    }

    public function destroySablon(Sablon $sablon)
    {
        $sablon->delete();
        return back()->with('success', 'Sablon berhasil dihapus');
    }

    // === MOCKUP ===
    public function storeMockup(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'angle' => 'required|string|max:50',
            'file_path' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        $path = $request->file('file_path')->store('mockups', 'public');

        Mockup::create([
            'produk_id' => $request->produk_id,
            'angle' => $request->angle,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Mockup berhasil ditambahkan');
    }

    public function updateMockup(Request $request, Mockup $mockup)
    {
        $request->validate([
            'angle' => 'required|string|max:50',
            'file_path' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        $data = ['angle' => $request->angle];

        // Jika user upload file baru → hapus lama dan simpan baru
        if ($request->hasFile('file_path')) {
            Storage::disk('public')->delete($mockup->file_path);
            $data['file_path'] = $request->file('file_path')->store('mockups', 'public');
        }

        $mockup->update($data);
        return back()->with('success', 'Mockup berhasil diperbarui');
    }

    public function destroyMockup(Mockup $mockup)
    {
        Storage::disk('public')->delete($mockup->file_path);
        $mockup->delete();
        return back()->with('success', 'Mockup berhasil dihapus');
    }

    // === UKURAN (GLOBAL) ===
    public function storeUkuran(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:20|unique:ukuran,nama',
            'tambahan_harga' => 'nullable|numeric|min:0',
        ]);

        Ukuran::create([
            'nama' => $request->nama,
            'tambahan_harga' => $request->tambahan_harga ?? 0,
        ]);
        return redirect()->back()->with('success', 'Ukuran berhasil ditambahkan');
    }

    public function updateUkuran(Request $request, Ukuran $ukuran)
    {
        $request->validate([
            'nama' => 'required|string|max:20|unique:ukuran,nama,' . $ukuran->id,
            'tambahan_harga' => 'nullable|numeric|min:0',
        ]);

        $ukuran->update($request->only('nama', 'tambahan_harga'));
        return redirect()->back()->with('success', 'Ukuran berhasil diperbarui');
    }

    public function destroyUkuran(Ukuran $ukuran)
    {
        $ukuran->delete();
        return response()->json(['success' => true]);
    }
}
