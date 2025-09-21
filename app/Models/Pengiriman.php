<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';
    protected $fillable = ['pesanan_id','kurir','layanan','resi','tgl_kirim','tgl_sampai'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
