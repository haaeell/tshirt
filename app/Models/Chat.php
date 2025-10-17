<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'message', 'is_read', 'file_name', 'file_path', 'file_type'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Ambil percakapan 2 arah
    public function scopeBetween($q, $a, $b)
    {
        return $q->where(function ($qq) use ($a, $b) {
            $qq->where('sender_id', $a)->where('receiver_id', $b);
        })->orWhere(function ($qq) use ($a, $b) {
            $qq->where('sender_id', $b)->where('receiver_id', $a);
        });
    }
}
