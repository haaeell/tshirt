<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        return view('home');
    }

    public function welcome()
    {
        $produk = Produk::where('aktif', true)
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', compact('produk'));
    }
}
