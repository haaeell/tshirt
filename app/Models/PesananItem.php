<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesananItem extends Model
{
    use HasFactory;

    protected $table = 'pesanan_item';
    protected $fillable = [
        'pesanan_id','produk_id','produk_varian_id',
        'nama_produk','warna','ukuran','lengan','bahan',
        'pakai_sablon','detail_sablon','qty','harga_satuan','subtotal'
    ];

    protected $casts = [
        'detail_sablon' => 'array',
        'pakai_sablon' => 'boolean',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
