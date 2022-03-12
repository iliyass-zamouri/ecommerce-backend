<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api/v1" middleware group. Enjoy building your API!
|
*/
// Public Routes :
Route::get('/products', [PublicController::class , 'allProducts'] );
Route::get('/categories', [PublicController::class , 'allCategories'] );
Route::get('/products/{slug}', [PublicController::class , 'showProduct'] );

// Login & Register
Route::post("login",[\App\Http\Controllers\AuthController::class,'login']);
Route::post("register",[\App\Http\Controllers\AuthController::class,'register']);


// Admin Protected Routes
Route::group(['prefix' => 'admin', 'middleware' => 'auth:sanctum'], function(){

});

// Users protected Routes
Route::group(['prefix' => 'user', 'middleware' => 'auth:sanctum'], function(){

});
