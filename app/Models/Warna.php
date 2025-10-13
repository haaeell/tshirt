<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warna extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'nama', 'hex'];
    protected $table = 'warna';

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
