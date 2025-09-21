<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','role',
    ];

    protected $hidden = [
        'password','remember_token',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function alamat()
    {
        return $this->hasMany(AlamatUser::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
}
