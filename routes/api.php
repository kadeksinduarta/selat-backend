<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Public Blog
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article}', [ArticleController::class, 'show']);

// Admin Authentication (PUBLIC)
Route::post('/admin/register', [AuthController::class, 'register']);
Route::post('/admin/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Protected Routes (Login Required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Info akun admin
    Route::get('/admin/me', function (Request $request) {
        return $request->user();
    });

    Route::prefix('dashboard')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | CRUD Articles (Admin Only)
        |--------------------------------------------------------------------------
        */
        Route::get('articles', [ArticleController::class, 'index']);
        Route::post('articles', [ArticleController::class, 'store']);
        Route::get('articles/{article}', [ArticleController::class, 'show']);
        Route::put('articles/{article}', [ArticleController::class, 'update']);
        Route::delete('articles/{article}', [ArticleController::class, 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | CRUD Products (Admin Only)
        |--------------------------------------------------------------------------
        */
        Route::get('products', [ProductController::class, 'index']);
        Route::post('products', [ProductController::class, 'store']);
        Route::get('products/{product}', [ProductController::class, 'show']);
        Route::put('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Checkout & Transaksi
    |--------------------------------------------------------------------------
    */
    Route::post('/checkout', [TransactionController::class, 'checkout']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
});
