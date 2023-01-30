<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hazard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HazardController extends Controller
{
    public function index(Request $request): JsonResponse {
        return response()->json(
            Hazard::all()->map( fn(Hazard $hazard) => $this->showHazard($hazard) )
        );
    }

    private function showHazard(Hazard $hazard): array {
        return [
            'id' => $hazard->id,
            'name' => $hazard->name
        ];
    }
}
