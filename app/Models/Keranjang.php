<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';
    protected $fillable = ['user_id','session_id'];

    public function items()
    {
        return $this->hasMany(KeranjangItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
