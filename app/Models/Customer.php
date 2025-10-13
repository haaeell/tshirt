<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // tambahkan ini biar pakai tabel 'customers'
    protected $table = 'customers';

    protected $fillable = [
        'user_id',
        'no_hp',
        'jenis_kelamin',
        'tgl_lahir',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
