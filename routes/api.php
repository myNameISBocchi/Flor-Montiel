<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\citieController;
use App\Http\Controllers\CommitteeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComunityController;
use App\Http\Controllers\CouncilController;
use App\Http\Controllers\countryController;
use App\Http\Controllers\personController;
use App\Http\Controllers\privilegesController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\rolePrivilegeController;
use App\Http\Controllers\stateController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/loggin', [AuthController::class, 'loggin']);

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
/*
Route::put('/committes/{id}',[CommitteeController::class, 'update']);
Route::delete('/committes/{id}',[CommitteeController::class, 'delete']);
*/
Route::post('/roles',[roleController::class, 'store']);
Route::get('/roles', [roleController::class, 'findAll']);
Route::put('/roles/{id}',[roleController::class, 'update']);
Route::delete('roles/{id}',[roleController::class, 'delete']);

Route::post('/privileges',[privilegesController::class, 'store']);
Route::get('/privileges',[privilegesController::class, 'findAll']);
Route::put('privileges/{id}',[privilegesController::class, 'update']);
Route::delete('privileges/{id}', [privilegesController::class, 'delete']);

Route::get('rolesPrivileges/{roleId}', [rolePrivilegeController::class, 'findPrivilegeByRoleId']);
Route::post('/rolesPrivileges', [rolePrivilegeController::class, 'store']);

Route::post('/peoples', [personController::class, 'store']);
Route::get('/peoples', [personController::class, 'findAll']);
Route::get('/peoples/{id}',[personController::class, 'findById']);
Route::put('/peoples/{id}',[personController::class, 'update']);
Route::delete('/peoples/{id}',[personController::class, 'delete']);

Route::post('/countries',[countryController::class, 'store']);
Route::get('/countries',[countryController::class, 'findAll']);
Route::put('countries/{id}',[countryController::class, 'update']);
Route::delete('/countries/{id}',[countryController::class, 'delete']);
Route::get('/countries/{country}',[countryController::class, 'findById']);

Route::post('/states',[stateController::class, 'store']);
Route::get('/states', [stateController::class, 'findAll']);
Route::put('/states/{id}', [stateController::class, 'update']);
Route::delete('/states/{id}',[stateController::class, 'delete']);

Route::post('/cities',[citieController::class, 'store']);
Route::get('/cities',[citieController::class, 'findALl']);
Route::put('/cities/{id}',[citieController::class, 'update']);
Route::delete('/cities/{id}',[citieController::class, 'delete']);

