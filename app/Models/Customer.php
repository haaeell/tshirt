<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','no_hp','jenis_kelamin','tgl_lahir','foto'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
