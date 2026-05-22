<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\citieController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\ComunityController;
use App\Http\Controllers\CouncilController;
use App\Http\Controllers\countryController;
use App\Http\Controllers\personController;
use App\Http\Controllers\privilegesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\rolePrivilegeController;
use App\Http\Controllers\stateController;
use Illuminate\Support\Facades\Route;

Route::post('/loggin', [AuthController::class, 'loggin'])->name('auth.login');

Route::middleware('auth')->group(function () {
    
    Route::get('/comunities', [ComunityController::class, 'findAll'])->name('comunities.index');
    Route::post('/comunities', [ComunityController::class, 'store'])->name('comunities.store');
    Route::put('/comunities/{id}', [ComunityController::class, 'update'])->name('comunities.update');
    Route::delete('/comunities/{id}', [ComunityController::class, 'delete'])->name('comunities.destroy');
    Route::post('/comunities/{id}/photo', [ComunityController::class, 'uploadPhoto'])->name('comunities.upload_photo');
    
    Route::get('/councils', [CouncilController::class, 'findAll'])->name('councils.index');
    Route::get('/councils/bycomunity/{comunityId}', [CouncilController::class, 'findByComunity'])->name('councils.by_comunity');
    Route::post('/councils', [CouncilController::class, 'store'])->name('councils.store');
    Route::put('/councils/{id}', [CouncilController::class, 'update'])->name('councils.update');
    Route::delete('/councils/{id}', [CouncilController::class, 'delete'])->name('councils.destroy');
    
    Route::get('/committees', [CommitteeController::class, 'findAll'])->name('committees.index');
    Route::get('/committees/subcommittees/{parentId}', [CommitteeController::class, 'findSubCommittee'])->name('committees.subcommittees');
    Route::post('/committees', [CommitteeController::class, 'store'])->name('committees.store');
    Route::put('/committees/{id}', [CommitteeController::class, 'update'])->name('committees.update');
    Route::delete('/committees/{id}', [CommitteeController::class, 'delete'])->name('committees.destroy');
    
    Route::get('/peoples', [personController::class, 'findAll'])->name('peoples.index');
    Route::get('/peoples/search', [personController::class, 'searchPerson'])->name('peoples.search');
    Route::get('/peoples/{id}', [personController::class, 'findById'])->name('peoples.show');
    Route::post('/peoples', [personController::class, 'store'])->name('peoples.store');
    Route::put('/peoples/{id}', [personController::class, 'update'])->name('peoples.update');
    Route::delete('/peoples/{id}', [personController::class, 'delete'])->name('peoples.destroy');
    Route::post('/peoples/{id}/photo', [personController::class, 'uploadPhoto'])->name('peoples.upload_photo');
    Route::post('/peoples/{id}/roles', [personController::class, 'assignRoles'])->name('peoples.assign_roles');
    
    Route::post('/peoples/update-own', [personController::class, 'updateOwn'])->name('peoples.update.own');
    Route::post('/peoples/upload-photo-own', [personController::class, 'uploadPhotoOwn'])->name('peoples.upload.photo.own');
    
    Route::get('/roles', [roleController::class, 'findAll'])->name('roles.index');
    Route::post('/roles', [roleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{id}', [roleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [roleController::class, 'delete'])->name('roles.destroy');
    
    Route::get('/privileges', [privilegesController::class, 'findAll'])->name('privileges.index');
    Route::post('/privileges', [privilegesController::class, 'store'])->name('privileges.store');
    Route::put('/privileges/{id}', [privilegesController::class, 'update'])->name('privileges.update');
    Route::delete('/privileges/{id}', [privilegesController::class, 'delete'])->name('privileges.destroy');
    
    Route::get('/role-privileges/{roleId}', [rolePrivilegeController::class, 'findPrivilegeByRoleId'])->name('role_privileges.index');
    Route::post('/role-privileges', [rolePrivilegeController::class, 'store'])->name('role_privileges.store');
    
    Route::get('/countries', [countryController::class, 'findAll'])->name('countries.index');
    Route::post('/countries', [countryController::class, 'store'])->name('countries.store');
    Route::put('/countries/{id}', [countryController::class, 'update'])->name('countries.update');
    Route::delete('/countries/{id}', [countryController::class, 'delete'])->name('countries.destroy');
    Route::get('/country/get-by-state/{stateId}', [countryController::class, 'getCountryByStateId'])->name('country.by_state');
    
    Route::get('/states', [stateController::class, 'findAll'])->name('states.index');
    Route::post('/states', [stateController::class, 'store'])->name('states.store');
    Route::put('/states/{id}', [stateController::class, 'update'])->name('states.update');
    Route::delete('/states/{id}', [stateController::class, 'delete'])->name('states.destroy');
    Route::get('/state/get-by-country/{countryId}', [stateController::class, 'getStateByCountryId'])->name('state.by_country');
    
    Route::get('/cities', [citieController::class, 'findAll'])->name('cities.index');
    Route::post('/cities', [citieController::class, 'store'])->name('cities.store');
    Route::put('/cities/{id}', [citieController::class, 'update'])->name('cities.update');
    Route::delete('/cities/{id}', [citieController::class, 'delete'])->name('cities.destroy');
    Route::get('/citie/get-by-state/{stateId}', [citieController::class, 'getCityByStateId'])->name('citie.by_state');
    
    Route::get('/reporte/voceros', [ReportController::class, 'imprimirVoceros'])->name('reports.voceros');
});