<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\SliderController;


use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register',[AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
 
Route::get('getCategory', [FrontendController::class, 'category']);
Route::get('fetchproducts/{slug}', [FrontendController::class, 'product']);
Route::get('viewproductdetail/{category_slug}/{product_slug}',[FrontendController::class, 'viewproduct']);
Route::post('add-to-cart', [CartController::class, 'addtocart']);
Route::get('cart', [CartController::class, 'viewCart']);
Route::put('cartUpdateQuantity/{id}/{scope}',[CartController::class, 'updateQuantity']);
Route::get('get_slider', [SliderController::class, 'index']);

Route::middleware(['auth:sanctum','isAdmin'])->group(function () {
    Route::get('/checkLogin', function(){
        return response()->json(['message' => 'You have been logged in', 'status' => 200], 200);
    });
    // Category
    Route::get('view_category',[CategoryController::class, 'index']);
    Route::post('add_Category', [CategoryController::class, 'store']);
    Route::get('edit_category/{id}', [CategoryController::class, 'edit']);
    Route::put('update_category/{id}', [CategoryController::class, 'update']);
    Route::put('delete_category/{id}', [CategoryController::class, 'delete']);
    Route::put('restore_category/{id}', [CategoryController::class, 'restore']);
    Route::put('destroy_category/{id}', [CategoryController::class, 'destroy']);
    Route::get('all_category', [CategoryController::class, 'getAll']);
    Route::get('garbage_category',[CategoryController::class, 'garbageView']);


    


    // product
    Route::post('add_product', [ProductController::class, 'store']);
    Route::get('view_product', [ProductController::class, 'index']);
    Route::get('edit_product/{id}', [ProductController::class, 'edit']);
    Route::post('update_product/{id}', [ProductController::class, 'update']);


    Route::post('add_slider', [SliderController::class, 'store']);
    Route::get('get_viewSlider', [SliderController::class, 'index']);
    Route::get('edit_slider/{id}', [SliderController::class, 'edit']);
    Route::post('update_slider/{id}', [SliderController::class, 'update']);


});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
