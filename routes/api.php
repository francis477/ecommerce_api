<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionContorller;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:sanctum']], function () {


// Auth Routh

Route::get('profile/{id}', [AuthController::class,'profile']);
Route::put('profile/{id}', [AuthController::class,'update_user_profile']);
Route::put('change_password/{id}', [AuthController::class,'change_user_password']);
Route::post('logout', [AuthController::class,'logout']);

    // Users Route
    Route::post('register', [UserController::class,'register']);
    Route::get('users', [UserController::class,'index']);
    Route::get('users/{id}', [UserController::class,'showUserById']);
    Route::put('users/{id}', [UserController::class,'updateUserById']);
    Route::delete('delete_user/{id}', [UserController::class,'deleteUserById']);

    // User Role
    Route::get('roles', [RoleController::class,'index']);
    Route::get('roles_permission', [RoleController::class,'rolewithPermission']);
    Route::post('add_role', [RoleController::class,'createRole']);
    Route::post('create_role', [RoleController::class,'store']);
    Route::get('show_role/{id}', [RoleController::class,'show']);
    Route::get('edit_role/{id}', [RoleController::class,'edit']);
    Route::put('update_role/{id}', [RoleController::class,'update']);
    Route::put('role/{id}', [RoleController::class,'updateRole']);
    Route::delete('delete_role/{id}', [RoleController::class,'destroy']);

       // Permission Route
       Route::get('all_permission', [PermissionContorller::class,'index']);
       Route::get('eidt_permission_data', [PermissionContorller::class,'edit_query']);
       Route::post('create_permission', [PermissionContorller::class,'store']);
       Route::get('get_permission/{id}', [PermissionContorller::class,'show']);
       Route::put('update_permission/{id}', [PermissionContorller::class,'update']);
       Route::delete('delete_permission/{id}', [PermissionContorller::class,'destroy']);

    // Category Route
    Route::get('category', [CategoryController::class,'index']);
    Route::post('create_category', [CategoryController::class,'store']);
    Route::get('category/{id}', [CategoryController::class,'show']);
    Route::put('category/{id}', [CategoryController::class,'update']);
    Route::delete('category/{id}', [CategoryController::class,'destroy']);


    // Brand Route
    Route::get('brand', [BrandController::class,'index']);
    Route::post('create_brand', [BrandController::class,'store']);
    Route::get('brand/{id}', [BrandController::class,'show']);
    Route::put('brand/{id}', [BrandController::class,'update']);
    Route::delete('brand/{id}', [BrandController::class,'destroy']);

    // Product Route
    Route::post('create_product', [ProductController::class,'store']);
    Route::post('add_pro_images', [ProductController::class,'add_more_image']);
    Route::get('all_product', [ProductController::class,'index']);
    Route::get('all_images/{pro_id}', [ProductController::class,'images']);
    Route::get('all_product_two', [ProductController::class,'get_product']);
    Route::put('update_image/{id}', [ProductController::class,'updateImage']);
    Route::put('update_product/{id}', [ProductController::class,'update']);
    Route::delete('delete_product/{id}', [ProductController::class,'destroy']);
    Route::delete('delete_product_img/{id}', [ProductController::class,'destroy_image']);

});

Route::get('home', [HomeController::class,'index']);

Route::post('auth', [LoginController::class,'login']);

Route::get('home_product', [HomeController::class,'home_product']);
Route::get('home_product/{id}', [HomeController::class,'home_product_id']);
Route::get('pos_product', [HomeController::class,'get_product']);

Route::get('home_category', [HomeController::class,'category']);
Route::get('home_brand', [HomeController::class,'brand']);

