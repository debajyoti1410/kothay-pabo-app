<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

/** LOGIN */
Route::post('/login', [AuthController::class,'login']);
Route::get('/login', [AuthController::class,'checkLogin'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    /** lOGOUT */
    Route::get('/logout',[AuthController::class,'logout']);
    Route::get('/update-token',[AuthController::class,'updateToken']);

    /** ROLE & PERMISSIONS */
    Route::get('/permissions', [RoleController::class,'getPermissions']);
    Route::get('/roles', [RoleController::class,'index']);
    Route::post('/role/store', [RoleController::class,'store']);

    /** USER */
    Route::controller(UserController::class)->group(function () {
        Route::get('/users','index');
        Route::group(['prefix'=>'user'], function(){
            Route::get('/permissions','getPermissions');
            Route::post('/store','store');
        });
    });
});
