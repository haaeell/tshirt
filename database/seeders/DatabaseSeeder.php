<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // === USERS ===
        $adminId = DB::table('users')->insertGetId([
            'nama' => 'Admin Toko',
            'email' => 'admin@tokodelapan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $customerId = DB::table('users')->insertGetId([
            'nama' => 'Haikal Customer',
            'email' => 'haikal@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('customers')->insert([
            'user_id' => $customerId,
            'no_hp' => '081234567890',
            'jenis_kelamin' => 'Laki-laki',
            'tgl_lahir' => '2000-01-01',
            'foto' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // === PRODUK ===
        $kaosId = DB::table('produk')->insertGetId([
            'nama' => 'Kaos Polos Premium',
            'jenis_produk' => 'kaos',
            'harga' => 75000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $kemejaId = DB::table('produk')->insertGetId([
            'nama' => 'Kemeja Flanel',
            'jenis_produk' => 'kemeja',
            'harga' => 150000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // === ATRIBUT ===
        DB::table('ukuran')->insert([
            ['nama' => 'S', 'tambahan_harga' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'M', 'tambahan_harga' => 5000, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'L', 'tambahan_harga' => 10000, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'XL', 'tambahan_harga' => 15000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('lengan')->insert([
            ['produk_id' => $kaosId, 'tipe' => 'pendek', 'tambahan_harga' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['produk_id' => $kaosId, 'tipe' => 'panjang', 'tambahan_harga' => 10000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('warna')->insert([
            ['produk_id' => $kaosId, 'nama' => 'Putih', 'hex' => '#FFFFFF', 'created_at' => $now, 'updated_at' => $now],
            ['produk_id' => $kaosId, 'nama' => 'Hitam', 'hex' => '#000000', 'created_at' => $now, 'updated_at' => $now],
            ['produk_id' => $kaosId, 'nama' => 'Merah', 'hex' => '#FF0000', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('bahan')->insert([
            ['produk_id' => $kaosId, 'nama' => 'Cotton Combed 30s', 'tambahan_harga' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['produk_id' => $kaosId, 'nama' => 'Cotton Bamboo', 'tambahan_harga' => 15000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('mockup')->insert([
            ['produk_id' => $kaosId, 'angle' => 'depan', 'file_path' => 'mockup/kaos-depan.png', 'created_at' => $now, 'updated_at' => $now],
            ['produk_id' => $kaosId, 'angle' => 'belakang', 'file_path' => 'mockup/kaos-belakang.png', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // === VOUCHER ===
        DB::table('voucher')->insert([
            [
                'kode' => 'PROMO10',
                'tipe' => 'persen',
                'nilai' => 10,
                'maks_diskon' => 20000,
                'min_belanja' => 100000,
                'mulai' => now()->subDays(1),
                'berakhir' => now()->addMonth(),
                'limit_pemakaian' => 100,
                'jumlah_dipakai' => 0,
                'aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'DISKON20K',
                'tipe' => 'nominal',
                'nilai' => 20000,
                'maks_diskon' => null,
                'min_belanja' => 150000,
                'mulai' => now()->subDays(1),
                'berakhir' => now()->addMonth(),
                'limit_pemakaian' => 50,
                'jumlah_dipakai' => 0,
                'aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // === ULASAN DUMMY ===
    }
}
