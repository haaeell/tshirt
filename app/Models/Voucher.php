<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'voucher';
    protected $fillable = [
        'kode','tipe','nilai','maks_diskon',
        'min_belanja','mulai','berakhir','limit_pemakaian','jumlah_dipakai','aktif'
    ];
}
