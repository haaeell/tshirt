<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomSablon extends Model
{
    use HasFactory;

    protected $table = 'custom_sablon';
    protected $fillable = [
        'pesanan_item_id', 'mockup_id',
        'file_path', 'preview_file',
        'posisi_x', 'posisi_y', 'scale', 'rotation'
    ];

    public function pesananItem()
    {
        return $this->belongsTo(PesananItem::class);
    }

    public function mockup()
    {
        return $this->belongsTo(Mockup::class);
    }
}
