<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\King;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KingController extends Controller
{
    public function index(Request $request): JsonResponse {
        return response()->json(
            King::all()->map( fn(King $king) => $this->showKingy($king) )
        );
    }

    private function showKing(King $king): array {
        return [
            'id' => $king->id,
            'name' => $king->name
        ];
    }
}
