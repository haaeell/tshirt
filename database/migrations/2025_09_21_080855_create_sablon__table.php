<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // === Hapus jika tabel sudah ada ===
        Schema::dropIfExists('ulasan_produk');
        Schema::dropIfExists('custom_sablon');
        Schema::dropIfExists('pesanan_item_detail');
        Schema::dropIfExists('pesanan_item');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('keranjang_item_detail');
        Schema::dropIfExists('keranjang_item');
        Schema::dropIfExists('keranjang');
        Schema::dropIfExists('mockup');
        Schema::dropIfExists('bahan');
        Schema::dropIfExists('warna');
        Schema::dropIfExists('lengan');
        Schema::dropIfExists('ukuran');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('voucher');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('users');

        // === USERS & CUSTOMERS ===
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->timestamp('email_terverifikasi')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'customer'])->default('customer');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('no_hp')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        // === PRODUK DAN ATRIBUT ===
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis_produk');
            $table->decimal('harga', 12, 2);
            $table->timestamps();
        });

        Schema::create('ukuran', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('tambahan_harga', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('lengan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->string('tipe');
            $table->decimal('tambahan_harga', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('warna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->string('nama');
            $table->string('hex', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->string('nama');
            $table->decimal('tambahan_harga', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('mockup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->string('angle');
            $table->string('file_path');
            $table->timestamps();
        });

        // === KERANJANG BELANJA ===
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('keranjang_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keranjang_id')->constrained('keranjang')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->string('warna')->nullable();
            $table->string('lengan')->nullable();
            $table->string('bahan')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->string('custom_sablon_url')->nullable();
            $table->string('rincian_tambahan')->nullable();
            $table->timestamps();
        });

        Schema::create('keranjang_item_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keranjang_item_id')->constrained('keranjang_item')->cascadeOnDelete();
            $table->string('ukuran');
<<<<<<< HEAD
            $table->string('lengan', 20);
=======
            $table->string('lengan', 20)->nullable();
>>>>>>> f4e151c8d22d41dd6dfef8a68a1f29971610d84e
            $table->integer('qty')->default(1);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // === PESANAN ===
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('total', 12, 2);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->string('kode_voucher')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->string('no_resi')->nullable();
            $table->enum('status', ['pending', 'dibayar', 'diproses', 'dikirim', 'selesai', 'batal'])->default('pending');
            $table->timestamps();
        });

        Schema::create('pesanan_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->string('warna')->nullable();
            $table->string('lengan')->nullable();
            $table->string('bahan')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->string('custom_sablon_url')->nullable();
            $table->string('rincian_tambahan')->nullable();
            $table->timestamps();
        });

        Schema::create('pesanan_item_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_item_id')->constrained('pesanan_item')->cascadeOnDelete();
            $table->string('ukuran');
            $table->string('lengan', 20);
            $table->integer('qty')->default(1);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        Schema::create('custom_sablon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_item_id')->nullable()->constrained('pesanan_item')->cascadeOnDelete();
            $table->foreignId('mockup_id')->constrained('mockup')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('preview_file');
            $table->integer('posisi_x');
            $table->integer('posisi_y');
            $table->decimal('scale', 5, 2)->default(1.0);
            $table->decimal('rotation', 5, 2)->default(0);
            $table->timestamps();
        });

        // === VOUCHER ===
        Schema::create('voucher', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->enum('tipe', ['persen', 'nominal']);
            $table->decimal('nilai', 12, 2)->nullable();
            $table->decimal('maks_diskon', 12, 2)->nullable();
            $table->decimal('min_belanja', 12, 2)->nullable();
            $table->dateTime('mulai')->nullable();
            $table->dateTime('berakhir')->nullable();
            $table->unsignedInteger('limit_pemakaian')->nullable();
            $table->unsignedInteger('jumlah_dipakai')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // === ULASAN ===
        Schema::create('ulasan_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_item_id')->nullable()->constrained('pesanan_item')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->timestamps();
            $table->unique(['pesanan_item_id', 'user_id']);
        });

        Schema::create('alamat_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->string('nama_penerima');
            $table->string('telepon');
            $table->text('alamat');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kode_pos', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan_produk');
        Schema::dropIfExists('voucher');
        Schema::dropIfExists('custom_sablon');
        Schema::dropIfExists('pesanan_item_detail');
        Schema::dropIfExists('pesanan_item');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('keranjang_item_detail');
        Schema::dropIfExists('keranjang_item');
        Schema::dropIfExists('keranjang');
        Schema::dropIfExists('mockup');
        Schema::dropIfExists('bahan');
        Schema::dropIfExists('warna');
        Schema::dropIfExists('lengan');
        Schema::dropIfExists('ukuran');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('users');
        Schema::dropIfExists('alamat_pengiriman');
    }
};
