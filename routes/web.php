<?php

use App\Http\Controllers\Member\CategoryController;
use App\Http\Controllers\Member\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',
])->group(function () {
   Route::middleware(['isMember'])->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

    Route::prefix('product')->group(function(){
        Route::get('',[ProductController::class,'index'])->name('member#product');
        Route::get('view/{id}',[ProductController::class,'show'])->name('member#product_show');
        Route::get('addPage',[ProductController::class,'create'])->name('member#product_add_page');
        Route::post('add',[ProductController::class,'store'])->name('member#product_add');
        Route::post('delete',[ProductController::class,'destroy'])->name('member#product_destroy');
        Route::get('edit/{id}',[ProductController::class,'edit'])->name('member#product_edit');
        Route::post('edit/{id}',[ProductController::class,'update'])->name('member#product_update');
    });
    Route::prefix('category')->group(function(){
        Route::get('',[CategoryController::class,'index'])->name('member#category');
        Route::post('store',[CategoryController::class,'store'])->name('member#category_add');
        Route::post('delete',[CategoryController::class,'destroy'])->name('member#category_destroy');
        Route::get('edit/{id}',[CategoryController::class,'edit'])->name('member#category_edit');
        Route::post('edit/{id}',[CategoryController::class,'update'])->name('member#category_update');
    });
   });
});

