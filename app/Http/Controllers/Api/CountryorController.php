<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Countryor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryorController extends Controller
{
    public function index(Request $request): JsonResponse {
        return response()->json(
            Countryor::all()->map( fn(Countryor $countryor) => $this->showCountryor($countryor) )
        );
    }

    private function showCountryor(Countryor $countryor): array {
        return [
            'id' => $countryor->id,
            'name' => $countryor->name
        ];
    }
}
