<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UlasanProduk extends Model
{
    use HasFactory;

    protected $table = 'ulasan_produk';
    protected $fillable = ['pesanan_item_id', 'pesanan_id', 'produk_id', 'user_id', 'rating', 'komentar'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pesananItem()
    {
        return $this->belongsTo(PesananItem::class);
    }
}
