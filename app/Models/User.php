<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['nama', 'email', 'password', 'role', 'is_online', 'last_seen_at'];
    protected $hidden = ['password', 'remember_token'];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function keranjang()
    {
        return $this->hasOne(Keranjang::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function ulasan()
    {
        return $this->hasMany(UlasanProduk::class);
    }
}
