<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Partner\PartnerController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    /** routes para Role **/

    Route::get('roles', [\App\Http\Controllers\Role\RoleController::class,'_index']);
    Route::get('roles/{id}', [\App\Http\Controllers\Role\RoleController::class,'_show']);
    Route::post('roles', [\App\Http\Controllers\Role\RoleController::class,'_store']);
    Route::put('roles/{id}', [\App\Http\Controllers\Role\RoleController::class,'_update']);
    Route::delete('roles/{id}', [\App\Http\Controllers\Role\RoleController::class,'_destroy']);

    /** routes para Partners **/
    Route::apiResource('partners',PartnerController::class);

    /** routes para Driver **/
    Route::apiResource('drivers', \App\Http\Controllers\Driver\DriverController::class);

    /** routes para Vehicle **/
    Route::apiResource('vehicles', \App\Http\Controllers\Vehicle\VehicleController::class);
});

/** routes para Auth **/
Route::prefix('auth')->group(function () {
    Route::middleware('auth:sanctum')
        ->get('logout',[\App\Http\Controllers\Auth\AuthController::class,'logout']);

    Route::post('login', [\App\Http\Controllers\Auth\AuthController::class,'login']);
    Route::post('register', [\App\Http\Controllers\Auth\AuthController::class,'register']);
});

Route::prefix('users')->group(function(){
    Route::apiResource('',UserController::class)->only(['index','update','show']);
});

