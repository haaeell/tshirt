<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosisiSablon extends Model
{
    use HasFactory;

    protected $table = 'posisi_sablon';
    protected $fillable = ['kode','nama'];
}
