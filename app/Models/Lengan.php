<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lengan extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'tipe', 'tambahan_harga'];
    protected $table = 'lengan';

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
