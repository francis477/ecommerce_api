<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionContorller;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\RoleController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::get('users', [AuthController::class, 'index']);
    Route::get('users/{id}', [AuthController::class, 'showUserById']);
    Route::put('users/{id}', [AuthController::class, 'updateUserById']);
    Route::delete('delete_user/{id}', [AuthController::class, 'deleteUserById']);
    Route::post('add_role', [RoleController::class, 'createRole']);
    Route::post('create_role', [RoleController::class, 'store']);
    Route::get('show_role/{id}', [RoleController::class, 'show']);
    Route::get('edit_role/{id}', [RoleController::class, 'edit']);
    Route::put('update_role/{id}', [RoleController::class, 'update']);
    Route::delete('delete_role/{id}', [RoleController::class, 'destroy']);
    Route::get('all_permission', [PermissionContorller::class, 'index']);
    Route::post('create_permission', [PermissionContorller::class, 'store']);
    Route::get('get_permission/{id}', [PermissionContorller::class, 'show']);
    Route::put('update_permission/{id}', [PermissionContorller::class, 'update']);
    Route::delete('delete_permission/{id}', [PermissionContorller::class, 'destroy']);

    // Category Route
    Route::get('category', [CategoryController::class, 'index']);
    Route::post('create_category', [CategoryController::class, 'store']);
    Route::get('category/{id}', [CategoryController::class, 'show']);
    Route::put('category/{id}', [CategoryController::class, 'update']);
    Route::delete('category/{id}', [CategoryController::class, 'destroy']);

    // Brand Route
    Route::get('brand', [BrandController::class, 'index']);
    Route::post('create_brand', [BrandController::class, 'store']);
    Route::get('brand/{id}', [BrandController::class, 'show']);
    Route::put('brand/{id}', [BrandController::class, 'update']);
    Route::delete('brand/{id}', [BrandController::class, 'destroy']);

    // Product Route

    Route::post('create_product', [ProductController::class, 'store']);
    Route::get('product', [ProductController::class, 'index']);
    Route::put('update_image/{id}', [ProductController::class, 'updateImage']);
    Route::put('update_product/{id}', [ProductController::class, 'update']);
    Route::get('update_product/{id}', [ProductController::class, 'destroy']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('home', [HomeController::class, 'index']);

Route::post('auth', [LoginController::class, 'login']);
