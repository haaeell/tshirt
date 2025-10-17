<?php

use App\Http\Controllers\UlasanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomSablonController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukVarianController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');

Auth::routes();

# ADMIN ROUTES
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::post('users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::put('users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    Route::resource('produk', ProdukController::class)->except(['show']);
    Route::resource('materials', MaterialController::class)->except(['show']);
    Route::resource('voucher', VoucherController::class)->except(['show']);

    Route::get('pesanan', [PesananController::class, 'index'])->name('admin.pesanan.index');
    Route::get('pesanan/{id}', [PesananController::class, 'show'])->name('admin.pesanan.show');
    Route::post('pesanan/{id}/status', [PesananController::class, 'updateStatus'])->name('admin.pesanan.updateStatus');
    Route::post('pesanan/{id}/approve', [PesananController::class, 'approvePayment'])->name('admin.pesanan.approve');
    Route::post('pesanan/{id}/reject', [PesananController::class, 'rejectPayment'])->name('admin.pesanan.reject');
    Route::post('pesanan/{id}/resi', [PesananController::class, 'updateResi'])->name('admin.pesanan.updateResi');



    Route::get('/ulasan', [UlasanController::class, 'index'])->name('admin.ulasan.index');

    Route::prefix('produk')->group(function () {
        Route::get('varian', [ProdukVarianController::class, 'index'])->name('produk.varian');

        // Warna
        Route::post('varian/warna', [ProdukVarianController::class, 'storeWarna'])->name('varian.warna.store');
        Route::put('varian/warna/{warna}', [ProdukVarianController::class, 'updateWarna'])->name('varian.warna.update');
        Route::delete('varian/warna/{warna}', [ProdukVarianController::class, 'destroyWarna'])->name('varian.warna.destroy');

        // Bahan
        Route::post('varian/bahan', [ProdukVarianController::class, 'storeBahan'])->name('varian.bahan.store');
        Route::put('varian/bahan/{bahan}', [ProdukVarianController::class, 'updateBahan'])->name('varian.bahan.update');
        Route::delete('varian/bahan/{bahan}', [ProdukVarianController::class, 'destroyBahan'])->name('varian.bahan.destroy');

        // Lengan
        Route::post('varian/lengan', [ProdukVarianController::class, 'storeLengan'])->name('varian.lengan.store');
        Route::put('varian/lengan/{lengan}', [ProdukVarianController::class, 'updateLengan'])->name('varian.lengan.update');
        Route::delete('varian/lengan/{lengan}', [ProdukVarianController::class, 'destroyLengan'])->name('varian.lengan.destroy');

        // Sablon
        Route::post('varian/sablon', [ProdukVarianController::class, 'storeSablon'])->name('varian.sablon.store');
        Route::put('varian/sablon/{sablon}', [ProdukVarianController::class, 'updateSablon'])->name('varian.sablon.update');
        Route::delete('varian/sablon/{sablon}', [ProdukVarianController::class, 'destroySablon'])->name('varian.sablon.destroy');

        // Ukuran
        Route::post('varian/ukuran', [ProdukVarianController::class, 'storeUkuran'])->name('varian.ukuran.store');
        Route::put('varian/ukuran/{ukuran}', [ProdukVarianController::class, 'updateUkuran'])->name('varian.ukuran.update');
        Route::delete('varian/ukuran/{ukuran}', [ProdukVarianController::class, 'destroyUkuran'])->name('varian.ukuran.destroy');

        // Mockup
        Route::post('varian/mockup', [ProdukVarianController::class, 'storeMockup'])->name('varian.mockup.store');
        Route::put('varian/mockup/{mockup}', [ProdukVarianController::class, 'updateMockup'])->name('varian.mockup.update');
        Route::delete('varian/mockup/{mockup}', [ProdukVarianController::class, 'destroyMockup'])->name('varian.mockup.destroy');
    });
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

    Route::post('voucher/check', [OrderController::class, 'checkVoucher'])->name('users.checkVoucher');

    # pembayaran
    Route::post('pesanan/{id}/upload-bukti', [OrderController::class, 'uploadBukti'])->name('users.orders.uploadBukti');
    Route::post('pesanan/{id}/status', [OrderController::class, 'updateStatus'])->name('users.orders.updateStatus');
    Route::post('pesanan/{id}/review-produk', [OrderController::class, 'reviewProduk'])->name('users.orders.reviewProduk');

    Route::post('/custom-sablon/store', [CustomSablonController::class, 'store'])->name('custom-sablon.store');
    Route::get('/custom-sablon', [CustomSablonController::class, 'create'])->name('custom-sablon.create');

    Route::get('/laporan/penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
    Route::get('/laporan/penjualan/data', [LaporanPenjualanController::class, 'data'])->name('laporan.penjualan.data');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/fetch/{user}', [ChatController::class, 'fetch'])->name('chat.fetch');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/ping', [ChatController::class, 'pingOnline'])->name('chat.ping');

    Route::post('/chat/typing', [ChatController::class, 'typing'])->name('chat.typing');
    Route::post('/chat/stop-typing', [ChatController::class, 'stopTyping'])->name('chat.stopTyping');
    Route::post('/chat/read/{user}', [ChatController::class, 'markAsRead'])->name('chat.read');
});
