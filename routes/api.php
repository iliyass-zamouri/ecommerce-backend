<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------|
| API Routes: v1                                                           |
|--------------------------------------------------------------------------|
|                                                                          |
| Here is where you can register API routes for your application. These    |
| routes are loaded by the RouteServiceProvider within a group which       |
| is assigned the "api/v1" middleware group. Enjoy building your API!      |
|                                                                          |
| -------------------  Current API version is:  api/v1  -------------------|
*/
// Login & Register
Route::post("login",[\App\Http\Controllers\AuthController::class,'login']);
Route::post("register",[\App\Http\Controllers\AuthController::class,'register']);

// Public Routes
Route::get('/products', [\App\Http\Controllers\PublicController::class , 'allProducts']);
Route::get('/categories', [\App\Http\Controllers\PublicController::class , 'allCategories']);
Route::get('/products/{slug}', [\App\Http\Controllers\PublicController::class , 'showProduct']);


// Log in protected routes
Route::group(['middleware' => 'auth:sanctum'], function(){

    // Admin sProtected Routes with admin custom middleware
    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function(){

        // Admin Data routes:
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'info']);
        Route::post('/', [\App\Http\Controllers\AdminController::class, 'update']);

        // Product Routes:
        Route::post('/products', [\App\Http\Controllers\AdminController::class, 'storeProduct']);
        Route::get('/products/delete/{product}', [\App\Http\Controllers\AdminController::class, 'deleteProduct']);
        Route::post('/products/photos', [\App\Http\Controllers\AdminController::class, 'addPhotoToProduct']);

        // Category routes:
        Route::post('/categories', [\App\Http\Controllers\AdminController::class, 'storeCategory']);
        Route::get('/categories/delete/{category}', [\App\Http\Controllers\AdminController::class, 'deleteCategory']);


        // Mark routes:
        Route::post('/marks', [\App\Http\Controllers\AdminController::class, 'storeMark']);

    });

    // Users protected Routes
    Route::group(['prefix' => 'user', 'middleware' => 'auth:sanctum'], function(){

        // User Data Routes:
        Route::get('/', [\App\Http\Controllers\UserController::class, 'info']);
        Route::post('/', [\App\Http\Controllers\UserController::class, 'update']);

    });

});

