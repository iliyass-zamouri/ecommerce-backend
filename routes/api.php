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

//------------------//
// Authentification //
//------------------//----------------------------------------------------------------//
Route::post("login",[\App\Http\Controllers\AuthController::class,'login']);
Route::post("register",[\App\Http\Controllers\AuthController::class,'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// email verification: send, verify
Route::get('email/verify', [\App\Http\Controllers\VerificationController::class, 'send'])->middleware('auth:sanctum')->middleware('auth:sanctum');
Route::get('email/verify/{id}/{hash}', [\App\Http\Controllers\VerificationController::class, 'verify'])->name('verification.verify');
// password resetting: forgot, reset
Route::post('password/forgot', [\App\Http\Controllers\PasswordController::class, 'forgotPassword']);
Route::post('password/reset', [\App\Http\Controllers\PasswordController::class, 'reset'])->name('password.reset');
// -----------------------------------------------------------------------------------//
//---------------//
// Public Routes //
//---------------//--------------------------------------------------------------------------------------//
Route::get('/products', [\App\Http\Controllers\PublicController::class , 'allProducts']);
Route::get('/products/{slug}', [\App\Http\Controllers\PublicController::class , 'showProduct']);
//-------------------------------------------------------------------------------------------------------//
Route::get('/categories', [\App\Http\Controllers\PublicController::class , 'allCategories']);
Route::get('/categories/{slug}', [\App\Http\Controllers\PublicController::class , 'productsbyCategory']);
//-------------------------------------------------------------------------------------------------------//
Route::get('/marks', [\App\Http\Controllers\PublicController::class , 'allMarks']);
Route::get('/marks/{slug}', [\App\Http\Controllers\PublicController::class , 'productsByMarks']);
//-------------------------------------------------------------------------------------------------------//
Route::post('/subscribe', [\App\Http\Controllers\PublicController::class , 'subscribe']);
//-------------------------------------------------------------------------------------------------------//

// Log in protected routes
//--------------------------------------------------------//
Route::group(['middleware' => 'auth:sanctum'], function(){

    // Admin sProtected Routes with admin custom middleware
    //-----------------------------------------------------------------------//
    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function(){
        //--------------------//
        // Admin Data routes: //
        //--------------------//--------------------------------------------------------//
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'info']);
        Route::post('/', [\App\Http\Controllers\AdminController::class, 'update']);
        Route::get('/ip', [\App\Http\Controllers\AdminController::class, 'getIp']);
        //------------------------------------------------------------------------------//
        //-----------------//
        // Product Routes: //
        //-----------------//-----------------------------------------------------------------------------//
        Route::post('/products', [\App\Http\Controllers\AdminController::class, 'storeProduct']);
        Route::get('/products/{product}/delete', [\App\Http\Controllers\AdminController::class, 'deleteProduct']);
        Route::post('/products/photos', [\App\Http\Controllers\AdminController::class, 'addPhotoToProduct']);
        Route::get('/products/photos/{photo}/delete', [\App\Http\Controllers\AdminController::class, 'deletePhotoFromProduct']);
        //------------------------------------------------------------------------------------------------//
        //------------------//
        // Category routes: //
        //------------------//----------------------------------------------------------------------------//
        Route::post('/categories', [\App\Http\Controllers\AdminController::class, 'storeCategory']);
        Route::get('/categories/{category}/delete', [\App\Http\Controllers\AdminController::class, 'deleteCategory']);
        //------------------------------------------------------------------------------------------------//
        //--------------//
        // Mark routes: //
        //--------------//---------------------------------------------------------------------------------//
        Route::post('/marks', [\App\Http\Controllers\AdminController::class, 'storeMark']);
        Route::get('/marks/{mark}/delete', [\App\Http\Controllers\AdminController::class, 'deleteMark']);
        //-------------------------------------------------------------------------------------------------//
    });

    // Users protected Routes
    //--------------------------------------------------------------------------------//
    Route::group(['prefix' => 'user', 'middleware' => 'auth:sanctum'], function(){
        //-------------------//
        // User Data Routes: //
        //-------------------//------------------------------------------------------------//
        Route::get('/', [\App\Http\Controllers\UserController::class, 'info']);
        Route::post('/', [\App\Http\Controllers\UserController::class, 'updateData']);
        Route::get('/subscribe', [\App\Http\Controllers\UserController::class , 'subscribe']);

        //---------------------------------------------------------------------------------//
        //------------------//
        // User cart routes //
        //------------------//------------------------------------------------------------------------------//
        Route::post('/cart', [\App\Http\Controllers\UserController::class, 'addToCart']);
        Route::get('/cart', [\App\Http\Controllers\UserController::class, 'getCart']);
        Route::get('/cart/wipe', [\App\Http\Controllers\UserController::class, 'wipeCart']);
        Route::post('/cart/update', [\App\Http\Controllers\UserController::class, 'updateCart']);
        Route::get('/cart/{product}/delete', [\App\Http\Controllers\UserController::class, 'deleteProductFromCart']);
        //--------------------------------------------------------------------------------------------------//
        //------------------//
        // User Wishlist routes //
        //------------------//------------------------------------------------------------------------------//
        Route::get('/wishlist/{slug}', [\App\Http\Controllers\UserController::class, 'addProductToWishlist']);
        Route::get('/wishlist', [\App\Http\Controllers\UserController::class, 'wishlist']);
        Route::get('/wishlist/wipe', [\App\Http\Controllers\UserController::class, 'wipeWishlist']);
        Route::get('/wishlist/{slug}/delete', [\App\Http\Controllers\UserController::class, 'deleteProductFromWishlist']);
        //--------------------------------------------------------------------------------------------------//
    });

});

