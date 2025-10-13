<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesananItemDetail extends Model
{
    use HasFactory;

    protected $table = 'pesanan_item_detail';
    protected $fillable = ['pesanan_item_id', 'ukuran', 'qty', 'harga_satuan', 'subtotal'];

    public function pesananItem()
    {
        return $this->belongsTo(PesananItem::class);
    }
}
