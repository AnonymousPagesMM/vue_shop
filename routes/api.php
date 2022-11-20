<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:sanctum')->post('/user/logout', function (Request $request) {
//     return $request->user()->tokens();
// });
Route::post('req_otp_code', [OtpController::class, 'create']); // email

Route::prefix('user')->group(function () {
    Route::post('register', [UserController::class, 'create']); //name,email,password,phone,gender
    Route::post('login', [UserController::class, 'store']); //name,email,password,phone,gender
});

Route::prefix('shop')->group(function () {
    Route::get('', [ShopController::class, 'index']);
    Route::get('{key}', [ShopController::class, 'search']);
    Route::get('shopPage/{id}',[ShopController::class,'show']);
});

Route::prefix('product')->group(function () {
    Route::get('{id}', [ProductController::class, 'show']); //id;
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('user/logout', [UserController::class, 'logout']);
    Route::prefix('cart')->group(function () {
        Route::post('', [CartController::class, 'index']);
        Route::post('add', [CartController::class, 'store']);
        Route::post('qty/change',[CartController::class,'update']); //id,qty
        Route::get('total_qty',[CartController::class,'total_qty']);
        Route::delete('delete/{id}',[CartController::class,'destroy']);//id
        Route::post('delete',[CartController::class,'destroy_all']);//id

    });
    Route::prefix('order')->group(function(){
        Route::get('old_detail',[OrderController::class,'old_detail']);
        Route::post('add',[OrderController::class,'store']);
        Route::get('',[OrderController::class,'index']);
        Route::post('info/{id}',[OrderController::class,'show']);
        Route::post('accept/{id}',[OrderController::class,'update']);
    });
});

Route::prefix('otp')->group(function(){
    Route::post('submit',[OtpController::class,'check_token'])->name('otp_check');
});

