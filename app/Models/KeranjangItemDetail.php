<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeranjangItemDetail extends Model
{
    protected $table = 'keranjang_item_detail';
    protected $fillable = ['keranjang_item_id', 'ukuran', 'lengan', 'qty', 'harga_satuan', 'subtotal'];

    public function item()
    {
        return $this->belongsTo(KeranjangItem::class, 'keranjang_item_id');
    }
}
