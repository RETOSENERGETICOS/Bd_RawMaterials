<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Countrysu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountrysuController extends Controller
{
    public function index(Request $request): JsonResponse {
        return response()->json(
            Countrysu::all()->map( fn(Countrysu $countrysu) => $this->showCountrysu($countrysu) )
        );
    }

    private function showCountrysu(Countrysu $countrysu): array {
        return [
            'id' => $countrysu->id,
            'name' => $countrysu->name
        ];
    }
}
