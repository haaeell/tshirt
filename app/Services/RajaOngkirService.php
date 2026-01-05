<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected string $key;
    protected string $baseUrl;

    public function __construct()
    {
        $this->key = config('services.rajaongkir.key');
        $this->baseUrl = rtrim(config('services.rajaongkir.base_url'), '/') . '/';
    }

    private function request(string $endpoint)
    {
        return Http::withHeaders([
            'key' => $this->key,
            'accept' => 'application/json',
        ])->get($this->baseUrl . $endpoint)->json();
    }

    // ===== LOCATION =====

    public function provinces()
    {
        return $this->request('destination/province');
    }

    public function cities($provinceId)
    {
        return $this->request("destination/city/{$provinceId}");
    }

    public function districts($cityId)
    {
        return $this->request("destination/district/{$cityId}");
    }

    public function subdistricts($districtId)
    {
        return $this->request("destination/sub-district/{$districtId}");
    }

    // ===== COST =====

    public function domesticCost($origin, $destination, $weight, $courier, $price = 'lowest')
    {
        return Http::withHeaders([
            'key' => $this->key,
            'accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post(
            'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost',
            compact('origin', 'destination', 'weight', 'courier', 'price')
        )->json();
    }
}
