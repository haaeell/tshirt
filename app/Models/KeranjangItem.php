<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeranjangItem extends Model
{
    protected $table = 'keranjang_item';
    protected $fillable = ['keranjang_id', 'produk_id', 'warna', 'bahan', 'lengan', 'subtotal', 'custom_sablon_url', 'rincian_tambahan'];
    protected $casts = [
        'rincian_tambahan' => 'array',
    ];

    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'keranjang_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function details()
    {
        return $this->hasMany(KeranjangItemDetail::class, 'keranjang_item_id');
    }
}
