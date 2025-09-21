<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::all();
        return view('admin.materials.index', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        Material::create($request->only('nama', 'deskripsi'));
        return redirect()->back()->with('success', 'Material berhasil ditambahkan');
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $material->update($request->only('nama', 'deskripsi'));
        return redirect()->back()->with('success', 'Material berhasil diperbarui');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return response()->json(['success' => true]);
    }
}
