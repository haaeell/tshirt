<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukVarianController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

Auth::routes();

# ADMIN ROUTES
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::post('users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::put('users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    Route::resource('produk', ProdukController::class)->except(['show']);
    Route::resource('produk-varian', ProdukVarianController::class)->except(['show']);
    Route::resource('materials', MaterialController::class)->except(['show']);
    Route::resource('voucher', VoucherController::class)->except(['show']);
});

# USER ROUTES
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    # produk
    Route::get('produk', [ProductController::class, 'index'])->name('users.produk.index');
    Route::get('produk/{id}', [ProductController::class, 'show'])->name('users.produk.show');

    # keranjang
    Route::get('keranjang', [CartController::class, 'index'])->name('users.cart.index');
    Route::post('keranjang/add', [CartController::class, 'store'])->name('users.cart.store');
    Route::put('keranjang/{id}', [CartController::class, 'update'])->name('users.cart.update');
    Route::delete('keranjang/{id}', [CartController::class, 'destroy'])->name('users.cart.destroy');

    # checkout & pesanan
    Route::get('checkout', [OrderController::class, 'checkout'])->name('users.checkout');
    Route::post('checkout', [OrderController::class, 'placeOrder'])->name('users.placeOrder');
    Route::get('pesanan', [OrderController::class, 'index'])->name('users.orders.index');
    Route::get('pesanan/{id}', [OrderController::class, 'show'])->name('users.orders.show');

    # pembayaran
    Route::post('pesanan/{id}/upload-bukti', [OrderController::class, 'uploadBukti'])->name('users.orders.uploadBukti');
});
