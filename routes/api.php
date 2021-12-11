<?php

use App\Http\Controllers\Admin\ManageCategory;
use App\Http\Controllers\Admin\ManageProduct;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Product;
use App\Http\Controllers\Api\Transaction;
use App\Http\Controllers\Seller\BecomeSeller;
use App\Http\Controllers\Seller\Seller;
use App\Http\Controllers\Seller\SellerProduct;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('products', [Product::class, 'index']);
Route::get('products/{id}', [Product::class, 'show']);
Route::get('categories', [ManageCategory::class, 'index']);
Route::get('logout', [AuthController::class, 'logout']);
Route::name('verify')->get('/verify/{token}', [AuthController::class, 'verifyuser']);

Route::middleware('auth:sanctum', 'blocked')->group(function(){
    Route::post('transaction', [Transaction::class, 'store']);
    Route::get('transaction', [Transaction::class, 'index']);
    Route::get('transaction/{id}', [Transaction::class, 'show']);
    Route::get('/becomeseller', [BecomeSeller::class, 'index']);
    Route::post('/becomeseller', [BecomeSeller::class, 'store']);
    Route::post('checkout', [Transaction::class, 'checkout']);
});

Route::middleware('auth:sanctum', 'blocked', 'validseller')->group(function(){
    Route::resource('/seller/product', SellerProduct::class);
    Route::get('/seller', [Seller::class, 'index']);
    Route::get('/seller/productlist', [SellerProduct::class, 'listproduct']);
});

Route::middleware('auth:sanctum', 'validadmin')->group(function(){
    Route::post('categories', [ManageCategory::class, 'store']);
});
