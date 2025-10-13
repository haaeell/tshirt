<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ukuran extends Model
{
    use HasFactory;
    protected $table = 'ukuran';

    protected $fillable = ['nama', 'tambahan_harga','produk_id'];
}
