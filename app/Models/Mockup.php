<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mockup extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'angle', 'file_path'];
    protected $table = 'mockup';

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function customSablon()
    {
        return $this->hasMany(CustomSablon::class);
    }
}
