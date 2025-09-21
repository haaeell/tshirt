<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeranjangItem extends Model
{
    use HasFactory;

    protected $table = 'keranjang_item';
    protected $fillable = [
        'keranjang_id','produk_id','produk_varian_id',
        'qty','harga_satuan','subtotal','pakai_sablon','detail_sablon'
    ];

    protected $casts = [
        'detail_sablon' => 'array',
        'pakai_sablon' => 'boolean',
    ];

    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function varian()
    {
        return $this->belongsTo(ProdukVarian::class, 'produk_varian_id');
    }
}
