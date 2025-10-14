<?php

namespace App\Http\Controllers;

use App\Models\CustomSablon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomSablonController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'image_data' => 'required|string',
            'pesanan_item_id' => 'required|integer',
            'mockup_id' => 'required|integer',
        ]);

        $imageData = $request->input('image_data');
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $imageName = 'custom_' . uniqid() . '.png';

        Storage::disk('public')->put('sablon/preview/' . $imageName, base64_decode($image));

        CustomSablon::create([
            'pesanan_item_id' => $request->pesanan_item_id,
            'mockup_id' => $request->mockup_id,
            'file_path' => 'sablon/preview/' . $imageName,
            'preview_file' => 'sablon/preview/' . $imageName,
            'posisi_x' => 0,
            'posisi_y' => 0,
            'scale' => 1,
            'rotation' => 0,
        ]);

        return response()->json([
            'success' => true,
            'preview_url' => asset('storage/sablon/preview/' . $imageName),
        ]);
    }
}
