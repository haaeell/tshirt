<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlamatPengiriman extends Model
{
    use HasFactory;

    protected $table = 'alamat_pengiriman';

    protected $fillable = [
        'pesanan_id',
        'nama_penerima',
        'telepon',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
