<?php

use App\Http\Controllers\Admin\ManageProduct;
use App\Http\Controllers\Api\AuthController;
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
Route::name('verify')->get('/verify/{token}', [AuthController::class, 'verifyuser']);
Route::middleware('auth:sanctum', 'blocked', 'validseller')->group(function(){
    Route::resource('/seller/product', SellerProduct::class);
    Route::get('/seller/productlist', [SellerProduct::class, 'listproduct']);
});

Route::middleware('auth:sanctum')->group(function(){
    Route::resource('/admin/product', ManageProduct::class);
});