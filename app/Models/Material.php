<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['nama','deskripsi'];

    public function varian()
    {
        return $this->hasMany(ProdukVarian::class);
    }
}
