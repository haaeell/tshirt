<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('user')->latest()->get();
        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'items.details', 'alamatPengiriman'])->findOrFail($id);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = $request->status;
        $pesanan->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function approvePayment($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if (!$pesanan->bukti_pembayaran) {
            return back()->with('error', 'Tidak dapat menyetujui. Bukti pembayaran belum diupload.');
        }

        $pesanan->status = 'diproses';
        $pesanan->save();

        return back()->with('success', 'Pembayaran telah disetujui. Pesanan masuk tahap proses.');
    }

    public function rejectPayment($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $pesanan->status = 'batal';
        $pesanan->save();

        return back()->with('error', 'Pembayaran ditolak dan pesanan dibatalkan.');
    }

    public function updateResi(Request $request, $id)
    {
        $request->validate([
            'no_resi' => 'required|string|max:100',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'diproses') {
            return back()->with('error', 'Nomor resi hanya bisa ditambahkan setelah pembayaran disetujui.');
        }

        $pesanan->no_resi = $request->no_resi;
        $pesanan->status = 'dikirim';
        $pesanan->save();

        return back()->with('success', 'Nomor resi berhasil ditambahkan dan pesanan dikirim.');
    }


}
