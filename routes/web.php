<?php

use App\Http\Controllers\Member\CategoryController;
use App\Http\Controllers\Member\OrderController;
use App\Http\Controllers\Member\ProductController;
use App\Http\Controllers\Member\SlideShowController;
use App\Http\Controllers\Member\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',
])->group(function () {

    Route::get('activate', [UserController::class, 'activate'])->name('activate');

    Route::middleware(['isMember'])->group(function () {
        Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
        Route::prefix('user')->group(function () {
            Route::post('update_image', [UserController::class, 'update_image'])->name('member#user_update_image');
        });
        Route::middleware(['activeUser'])->group(function () {
            Route::prefix('product')->group(function () {
                Route::get('', [ProductController::class, 'index'])->name('member#product');
                Route::get('view/{id}', [ProductController::class, 'show'])->name('member#product_show');
                Route::get('addPage', [ProductController::class, 'create'])->name('member#product_add_page');
                Route::post('add', [ProductController::class, 'store'])->name('member#product_add');
                Route::post('delete', [ProductController::class, 'destroy'])->name('member#product_destroy');
                Route::get('edit/{id}', [ProductController::class, 'edit'])->name('member#product_edit');
                Route::post('edit/{id}', [ProductController::class, 'update'])->name('member#product_update');
            });
            Route::prefix('category')->group(function () {
                Route::get('', [CategoryController::class, 'index'])->name('member#category');
                Route::post('store', [CategoryController::class, 'store'])->name('member#category_add');
                Route::post('delete', [CategoryController::class, 'destroy'])->name('member#category_destroy');
                Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('member#category_edit');
                Route::post('edit/{id}', [CategoryController::class, 'update'])->name('member#category_update');
            });
            Route::prefix('order')->group(function () {
                Route::get('', [OrderController::class, 'index'])->name('member#order');
                Route::get('{id}', [OrderController::class, 'show'])->name('member#order_info');
                Route::get('accept/{id}', [OrderController::class, 'accept'])->name('member#order_accept');
                Route::post('reject/{id}', [OrderController::class, 'reject'])->name('member#order_reject');
                Route::post('remark/{id}', [OrderController::class, 'remark'])->name('member#order_remark');
                Route::post('deliver_date/{id}', [OrderController::class, 'deliver_date'])->name('member#order_deliver_date');
                Route::get('delivered/{id}', [OrderController::class, 'delivered'])->name('member#order_delivered');
            });

            Route::prefix('slide_show')->group(function () {
                Route::get('', [SlideShowController::class, 'index'])->name('member#slideShow');
                Route::post('insert', [SlideShowController::class, 'store'])->name('member#slideShow_insert');
                Route::get('num_change/{old_num}/{new_num}', [SlideShowController::class, 'number_change'])->name('member#slideShow_num_change');
                Route::get('delete/{id}',[SlideShowController::class,'destroy'])->name('member#slideShow_delete');
            });
        });
    });
});
