<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class OngkirController extends Controller
{
    protected RajaOngkirService $rajaongkir;

    public function __construct(RajaOngkirService $rajaongkir)
    {
        $this->rajaongkir = $rajaongkir;
    }

    // ================== LOCATION ==================

    public function provinces()
    {
        $res = $this->rajaongkir->provinces();
        return response()->json($res['data'] ?? []);
    }

    public function cities($provinceId)
    {
        $res = $this->rajaongkir->cities($provinceId);
        return response()->json($res['data'] ?? []);
    }

    public function districts($cityId)
    {
        $res = $this->rajaongkir->districts($cityId);
        return response()->json($res['data'] ?? []);
    }

    public function subdistricts($districtId)
    {
        $res = $this->rajaongkir->subdistricts($districtId);
        return response()->json($res['data'] ?? []);
    }

    // ================== COST ==================

    public function cost(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required',
        ]);

        $res = $this->rajaongkir->domesticCost(
            $request->origin,
            $request->destination,
            $request->weight,
            $request->courier,
            'lowest'
        );

        return response()->json($res['data'] ?? []);
    }
}
