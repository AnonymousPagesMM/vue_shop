<?php

use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('req_otp_code',[OtpController::class,'create']); // email

Route::prefix('user')->group(function () {
    Route::post('register',[UserController::class,'register']); //name,email,password,phone
    Route::post('active',[UserController::class,'active']); //email,code,device
    Route::post('login',[UserController::class,'login']);//email,password
});
