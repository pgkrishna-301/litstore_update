<?php

use App\Http\Controllers\Api\CategoryController as ApiCategoryController;
use App\Http\Controllers\ArchitectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HideController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PlayoBannerController;
use App\Http\Controllers\PlayoBookBannerController;
use App\Http\Controllers\ProductOrderController;
use App\Http\Controllers\ProfessionController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SportLightController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\WishlistController;
use App\Models\OrderDetail;



// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/users', [AuthController::class, 'getAllUsers']);
Route::post('/logout-all-devices', [AuthController::class, 'logoutAllDevices']);

Route::get('/hideuser/{id}', [AuthController::class, 'getUserById']);






Route::get('/user/profile', [AuthController::class, 'getUserProfile']);
Route::post('/user/update', [AuthController::class, 'updateUser']);
Route::put('/update/{id}', [AuthController::class, 'updateHide']);
Route::post('/update/{id}', [AuthController::class, 'updateHide']);

// Product Routes
Route::post('/products', [ProductController::class, 'productstored']);
Route::post('/products/update/{id}', [ProductController::class, 'updateProduct']);
Route::delete('/products/delete/{id}', [ProductController::class, 'deleteProduct']);
Route::get('/products/list', [ProductController::class, 'getAllProducts']);
Route::get('/products/list/{id}', [ProductController::class, 'getProductById']);


// Banner Routes
Route::post('/banners', [BannerController::class, 'store']);

// Category Routes
Route::post('/categories', [CategoryController::class, 'categorystore']);
Route::get('/categories/list', [CategoryController::class, 'categorylist']);

// Shipping Routes
Route::post('/shipping', [ShippingController::class, 'store']);
Route::put('/shipping/update', [ShippingController::class, 'update']);
Route::get('/shippings/get', [ShippingController::class, 'index']);
Route::delete('/shipping/{user_id}', [ShippingController::class, 'destroy']);
Route::get('/shipping/get/{user_id}', [ShippingController::class, 'show']);


 // Order API

 Route::post('/order-details', [OrderDetailController::class, 'store']);
 Route::get('/order-details/get', [OrderDetailController::class, 'getAll']);
 Route::put('order-details/{id}', [OrderDetailController::class, 'update']);
 Route::get('/order-details/get/{id}', [OrderDetailController::class, 'getById']);
 Route::get('order-details/user/{userId}', [OrderDetailController::class, 'getByUserId']);
 Route::post('/store-order', [ProductOrderController::class, 'store']);
 Route::put('/order/update/{order_id}', [OrderDetailController::class, 'updateorderId']);
 Route::get('/get/order/{order_id}', [OrderDetailController::class, 'getOrderByOrderId']);




 Route::post('/cart-items', [CartItemController::class, 'store']);
 Route::get('/cart-items/get/{user_id}', [CartItemController::class, 'index']);
 Route::delete('/cart-items/{id}', [CartItemController::class, 'destroy']);

 Route::post('super-admin/register', [SuperAdminController::class, 'register']);
 Route::post('super-admin/login', [SuperAdminController::class, 'login']);
 Route::post('super-admin/logout', [SuperAdminController::class, 'logout']);
 Route::get('/super-admins/get', [SuperAdminController::class, 'getAllDetails']);
 Route::post('super-admin/update/{id}', [SuperAdminController::class, 'update']);

 Route::post('/wishlists', [WishlistController::class, 'store']);
 Route::get('/wishlists/get/{user_id}', [WishlistController::class, 'getByUserId']);
 Route::get('/wishlists/get-all', [WishlistController::class, 'getAllWishlists']);
 Route::delete('/wishlists/delete/{product_id}', [WishlistController::class, 'deleteByProductId']);

 Route::post('/playo_banner', [PlayoBannerController::class, 'store']);
 Route::get('/playo_banner/get', [PlayoBannerController::class, 'getImages']);
 Route::post('/playo-banners/{id}', [PlayoBannerController::class, 'update']); 
 Route::delete('/playo-banners/{id}', [PlayoBannerController::class, 'destroy']);

 Route::post('/playo_bookbanner', [PlayoBookBannerController::class, 'store']);
 Route::get('/playo_bookbanner/get', [PlayoBookBannerController::class, 'getImages']);

 Route::post('/sport_light', [SportLightController::class, 'store']);
 Route::get('/sport_light/get', [SportLightController::class, 'getImages']);

 Route::post('/architects', [ArchitectController::class, 'store']);
 Route::get('/architects/get', [ArchitectController::class, 'getAllArchitects']);

 Route::put('/architects/update/{ph_no}', [ArchitectController::class, 'update']);
 Route::put('/architects/update-all', [ArchitectController::class, 'updateAll']);

 Route::post('/profession', [ProfessionController::class, 'store']);
 Route::get('/get/profession', [ProfessionController::class, 'getProfessions']);


 Route::post('/hide', [HideController::class, 'store']); 
 Route::put('/hide/{id}', [HideController::class, 'update']); 


 


// php artisan serve --host=0.0.0.0
