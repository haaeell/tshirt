<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bahan extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'nama', 'tambahan_harga'];
    protected $table = 'bahan';

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
