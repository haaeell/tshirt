<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sablon extends Model
{
    use HasFactory;

    protected $fillable = ['jenis', 'ukuran', 'tambahan_harga'];
    protected $table = 'sablon';

    public function keranjangItem()
    {
        return $this->hasMany(KeranjangItem::class);
    }

    public function pesananItem()
    {
        return $this->hasMany(PesananItem::class);
    }
}
