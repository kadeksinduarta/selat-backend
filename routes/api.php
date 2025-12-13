<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return response()->json(['message' => 'Selamat datang di API Desa Selat']);
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// User Authentication
Route::post('/register', [AuthController::class, 'registerUser']);
Route::post('/login', [AuthController::class, 'loginUser']);

// Admin Authentication
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);

// Public Articles & Products
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article}', [ArticleController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Management (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::prefix('dashboard')->group(function () {
        // Articles
        Route::get('articles', [ArticleController::class, 'index']);
        Route::post('articles', [ArticleController::class, 'store']);
        Route::get('articles/{article}', [ArticleController::class, 'show']);
        Route::put('articles/{article}', [ArticleController::class, 'update']);
        Route::delete('articles/{article}', [ArticleController::class, 'destroy']);

        // Products
        Route::get('products', [ProductController::class, 'index']);
        Route::post('products', [ProductController::class, 'store']);
        Route::get('products/{product}', [ProductController::class, 'show']);
        Route::put('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'destroy']);

        // Users
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);

        // Transactions (Admin)
        Route::get('transactions', [TransactionController::class, 'adminIndex']);
        Route::put('transactions/{id}', [TransactionController::class, 'updateStatus']);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Management (Super Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::get('admins', [AdminController::class, 'index']);
    Route::post('admins', [AdminController::class, 'store']);
    Route::get('admins/{id}', [AdminController::class, 'show']);
    Route::put('admins/{id}', [AdminController::class, 'update']);
    Route::delete('admins/{id}', [AdminController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | User Profile & Addresses
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::get('/addresses/{id}', [AddressController::class, 'show']);
    Route::put('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
    Route::post('/addresses/{id}/set-default', [AddressController::class, 'setDefault']);

    /*
    |--------------------------------------------------------------------------
    | Checkout & Transaksi
    |--------------------------------------------------------------------------
    */
    Route::post('/checkout', [TransactionController::class, 'checkout']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
});
