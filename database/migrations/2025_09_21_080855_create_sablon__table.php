<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('users');
        // === USERS & CUSTOMERS ===
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin','customer'])->default('customer'); // role
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('no_hp')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('foto')->nullable(); // opsional: foto profil
            $table->timestamps();
        });

        // === MASTER DATA ===
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('posisi_sablon', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // depan, belakang, dll
            $table->string('nama');
            $table->timestamps();
        });

        // === PRODUK ===
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis'); //kaos kemeja dll
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('harga')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('produk_varian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('warna');
            $table->enum('ukuran', ['XS','S','M','L','XL','XXL','XXXL']);
            $table->enum('lengan', ['pendek','panjang']);
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedBigInteger('harga')->nullable();
            $table->timestamps();
            $table->unique(['produk_id','warna','ukuran','lengan','material_id'], 'unik_varian');
        });

        // === KERANJANG ===
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->timestamps();
        });

        Schema::create('keranjang_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keranjang_id')->constrained('keranjang')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->foreignId('produk_varian_id')->nullable()->constrained('produk_varian')->nullOnDelete();
            $table->unsignedInteger('qty')->default(1);
            $table->unsignedBigInteger('harga_satuan')->default(0);
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->boolean('pakai_sablon')->default(false);
            $table->json('detail_sablon')->nullable();
            $table->timestamps();
        });

        // === VOUCHER ===
        Schema::create('voucher', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->enum('tipe', ['persen','nominal']);
            $table->unsignedBigInteger('nilai',)->nullable();
            $table->unsignedBigInteger('maks_diskon')->nullable();
            $table->unsignedBigInteger('min_belanja')->nullable();
            $table->dateTime('mulai')->nullable();
            $table->dateTime('berakhir')->nullable();
            $table->unsignedInteger('limit_pemakaian')->nullable();
            $table->unsignedInteger('jumlah_dipakai')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // === ALAMAT ===
        Schema::create('alamat_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_penerima');
            $table->string('telepon');
            $table->string('alamat');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kode_pos', 10);
            $table->boolean('default')->default(false);
            $table->timestamps();
        });

        // === PESANAN ===
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_konfirmasi',
                'diproses',
                'dikirim',
                'sampai',
                'selesai',
                'batal'
            ])->default('menunggu_pembayaran');
            $table->string('nama_penerima');
            $table->string('telepon');
            $table->string('alamat');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kode_pos', 10);
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('diskon')->default(0);
            $table->unsignedBigInteger('ongkir')->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->string('voucher_kode')->nullable();
            $table->unsignedBigInteger('voucher_nilai')->default(0);
            $table->timestamps();
        });

        Schema::create('pesanan_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->foreignId('produk_varian_id')->nullable()->constrained('produk_varian')->nullOnDelete();
            $table->string('nama_produk');
            $table->string('warna')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('lengan')->nullable();
            $table->string('bahan')->nullable();
            $table->boolean('pakai_sablon')->default(false);
            $table->json('detail_sablon')->nullable();
            $table->unsignedInteger('qty')->default(1);
            $table->unsignedBigInteger('harga_satuan')->default(0);
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->timestamps();
        });

        // === PEMBAYARAN ===
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->enum('metode', ['transfer','cod','lainnya'])->default('transfer');
            $table->unsignedBigInteger('jumlah');
            $table->enum('status', ['pending','terkonfirmasi','ditolak'])->default('pending');
            $table->string('bukti')->nullable();
            $table->timestamps();
        });

        // === PENGIRIMAN ===
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->string('kurir')->nullable();
            $table->string('layanan')->nullable();
            $table->string('resi')->nullable();
            $table->dateTime('tgl_kirim')->nullable();
            $table->dateTime('tgl_sampai')->nullable();
            $table->timestamps();
        });

        // === ULASAN ===
        Schema::create('ulasan_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_item_id')->constrained('pesanan_item')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->timestamps();
            $table->unique(['pesanan_item_id','user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan_produk');
        Schema::dropIfExists('pengiriman');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('pesanan_item');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('alamat_user');
        Schema::dropIfExists('voucher');
        Schema::dropIfExists('keranjang_item');
        Schema::dropIfExists('keranjang');
        Schema::dropIfExists('produk_varian');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('posisi_sablon');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('users');
    }
};
