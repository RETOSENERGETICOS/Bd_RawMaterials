<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    RoleController,
    UserController,
    ToolController,
    GroupController,
    FamilyController,
    CountrysuController,
    CountryorController,
    HazardController,
    KingController,
    UploadController
};

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(static function() {
    Route::get('/user/verify', [AuthController::class, 'verify']);
    Route::get('/roles', [RoleController::class, 'index']);
    Route::delete('/uploads', [UploadController::class, 'destroy']);
    Route::post('/search', [ToolController::class, 'search']);
    Route::get('/history', [ToolController::class, 'history']);
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::apiResources([
        'users' => UserController::class,
        'tools' => ToolController::class,
        'countrysus' => CountrysupController::class,
        'families' => FamilyController::class,
        'countryors' => CountryorController::class,
        'hazards' => HazardController::class,
        'kings' => KingController::class,
        'uploads' => UploadController::class,
    ]);
});
