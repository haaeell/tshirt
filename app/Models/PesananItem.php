<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesananItem extends Model
{
    use HasFactory;

    protected $table = 'pesanan_item';
    protected $fillable = ['pesanan_id', 'produk_id', 'warna', 'lengan', 'sablon_id', 'bahan', 'subtotal', 'custom_sablon_url'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function details()
    {
        return $this->hasMany(PesananItemDetail::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }


    public function sablon()
    {
        return $this->belongsTo(Sablon::class);
    }

    public function detail()
    {
        return $this->hasMany(PesananItemDetail::class);
    }

    public function customSablon()
    {
        return $this->hasMany(CustomSablon::class);
    }
}
