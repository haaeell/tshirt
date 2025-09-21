<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = ['nama','jenis','deskripsi','harga','aktif'];

    public function varian()
    {
        return $this->hasMany(ProdukVarian::class);
    }

    public function pesananItem()
    {
        return $this->hasMany(PesananItem::class);
    }
}
