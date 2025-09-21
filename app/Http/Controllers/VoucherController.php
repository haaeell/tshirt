<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('admin.voucher.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:voucher,kode',
            'tipe' => 'required|in:persen,nominal',
            'nilai' => 'required|numeric|min:0',
            'maks_diskon' => 'nullable|numeric|min:0',
            'min_belanja' => 'nullable|numeric|min:0',
            'mulai' => 'nullable|date',
            'berakhir' => 'nullable|date|after_or_equal:mulai',
            'limit_pemakaian' => 'nullable|integer|min:0',
            'aktif' => 'boolean'
        ]);

        Voucher::create($request->all());
        return redirect()->back()->with('success', 'Voucher berhasil ditambahkan');
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'kode' => 'required|unique:voucher,kode,' . $voucher->id,
            'tipe' => 'required|in:persen,nominal',
            'nilai' => 'required|numeric|min:0',
            'maks_diskon' => 'nullable|numeric|min:0',
            'min_belanja' => 'nullable|numeric|min:0',
            'mulai' => 'nullable|date',
            'berakhir' => 'nullable|date|after_or_equal:mulai',
            'limit_pemakaian' => 'nullable|integer|min:0',
            'aktif' => 'boolean'
        ]);

        $voucher->update($request->all());
        return redirect()->back()->with('success', 'Voucher berhasil diperbarui');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return response()->json(['success' => true]);
    }
}
