<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukVarian extends Model
{
    use HasFactory;

    protected $table = 'produk_varian';
    protected $fillable = ['produk_id','sku','warna','ukuran','lengan','material_id','stok','harga'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
