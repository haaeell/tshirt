<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanan';

    protected $fillable = [
        'user_id', 'total', 'diskon', 'kode_voucher',
        'bukti_pembayaran', 'no_resi', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PesananItem::class);
    }

    public function alamatPengiriman()
{
    return $this->hasOne(AlamatPengiriman::class);
}
}
