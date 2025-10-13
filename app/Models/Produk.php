<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'jenis_produk', 'harga'];
    protected $table = 'produk';

    public function warna()
    {
        return $this->hasMany(Warna::class);
    }

    public function bahan()
    {
        return $this->hasMany(Bahan::class);
    }

    public function lengan()
    {
        return $this->hasMany(Lengan::class);
    }

    public function mockup()
    {
        return $this->hasMany(Mockup::class);
    }

    public function pesananItem()
    {
        return $this->hasMany(PesananItem::class);
    }

    public function sablon()
    {
        return $this->hasMany(Sablon::class);
    }

    public function ulasan()
    {
        return $this->hasMany(UlasanProduk::class, 'produk_id');
    }
}
