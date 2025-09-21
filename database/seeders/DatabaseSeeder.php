<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === USERS & CUSTOMERS ===
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Administrator',
            'email' => 'admin@sablon.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $customerId = DB::table('users')->insertGetId([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@sablon.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('customers')->insert([
            'user_id' => $customerId,
            'no_hp' => '08123456789',
            'jenis_kelamin' => 'Laki-laki',
            'tgl_lahir' => '1995-05-10',
            'foto' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === MATERIALS ===
        $cotton = DB::table('materials')->insertGetId([
            'nama' => 'Cotton Combed 30s',
            'deskripsi' => 'Bahan adem dan lembut, cocok untuk kaos.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $polyester = DB::table('materials')->insertGetId([
            'nama' => 'Polyester',
            'deskripsi' => 'Bahan ringan, cepat kering.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === POSISI SABLON ===
        DB::table('posisi_sablon')->insert([
            ['kode' => 'depan', 'nama' => 'Bagian Depan', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'belakang', 'nama' => 'Bagian Belakang', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'dada_kiri', 'nama' => 'Dada Kiri', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'dada_kanan', 'nama' => 'Dada Kanan', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'lengan_kiri', 'nama' => 'Lengan Kiri', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'lengan_kanan', 'nama' => 'Lengan Kanan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // === PRODUK ===
        $kaosId = DB::table('produk')->insertGetId([
            'nama' => 'Kaos Polos',
            'jenis' => 'kaos',
            'deskripsi' => 'Kaos polos berbagai warna dan ukuran.',
            'harga' => 50000,
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kemejaId = DB::table('produk')->insertGetId([
            'nama' => 'Kemeja Polos',
            'jenis' => 'kemeja',
            'deskripsi' => 'Kemeja polos bahan adem.',
            'harga' => 120000,
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === PRODUK VARIAN ===
        DB::table('produk_varian')->insert([
            [
                'produk_id' => $kaosId,
                'sku' => 'KAOS-HITAM-M',
                'warna' => 'Hitam',
                'ukuran' => 'M',
                'lengan' => 'pendek',
                'material_id' => $cotton,
                'stok' => 100,
                'harga' => 55000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'produk_id' => $kaosId,
                'sku' => 'KAOS-PUTIH-L',
                'warna' => 'Putih',
                'ukuran' => 'L',
                'lengan' => 'pendek',
                'material_id' => $cotton,
                'stok' => 80,
                'harga' => 55000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'produk_id' => $kemejaId,
                'sku' => 'KEM-BIRU-L',
                'warna' => 'Biru',
                'ukuran' => 'L',
                'lengan' => 'panjang',
                'material_id' => $polyester,
                'stok' => 50,
                'harga' => 125000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // === VOUCHER ===
        DB::table('voucher')->insert([
            [
                'kode' => 'DISKON10',
                'tipe' => 'persen',
                'nilai' => 10,
                'maks_diskon' => 20000,
                'min_belanja' => 100000,
                'mulai' => Carbon::now()->subDays(1),
                'berakhir' => Carbon::now()->addDays(30),
                'limit_pemakaian' => 100,
                'jumlah_dipakai' => 0,
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'HEMAT50K',
                'tipe' => 'nominal',
                'nilai' => 50000,
                'maks_diskon' => null,
                'min_belanja' => 250000,
                'mulai' => Carbon::now()->subDays(1),
                'berakhir' => Carbon::now()->addDays(30),
                'limit_pemakaian' => 50,
                'jumlah_dipakai' => 0,
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
