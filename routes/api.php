<?php

use App\Http\Controllers\CommitteeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComunityController;
use App\Http\Controllers\CouncilController;
use App\Models\Council;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/comunities', [ComunityController::class, 'store']);
Route::get('/comunities', [ComunityController::class, 'findAll']);
Route::put('/comunities/{id}', [ComunityController::class, 'update']);
Route::delete('/comunities/{id}', [ComunityController::class, 'delete']);
Route::post('/comunities/{id}/photo', [ComunityController::class, 'uploadPhoto']);

Route::post('/councils', [CouncilController::class, 'store']);
Route::get('/councils', [CouncilController::class, 'findAll']);
Route::put('/councils/{id}',[CouncilController::class, 'update']);
Route::delete('/councils/{id}',[CouncilController::class, 'delete']);

Route::post('/committes',[CommitteeController::class, 'store']);
Route::get('/committes',[CommitteeController::class, 'findAll']);
Route::put('/committes/{id}',[CommitteeController::class, 'update']);
