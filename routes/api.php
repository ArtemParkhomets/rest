<?php

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

Route::group(['prefix' => 'authors'], function() {
    Route::get('/', [\App\Http\Controllers\API\AuthorController::class, 'all']);
    Route::get('/{param}', [\App\Http\Controllers\API\AuthorController::class, 'getByParameter']);
});
Route::group(['prefix' => 'categories'], function() {
    Route::get('/', [\App\Http\Controllers\API\CategoryController::class, 'all']);
    Route::get('/{param}', [\App\Http\Controllers\API\CategoryController::class, 'getByParameter']);
});
Route::group(['prefix' => 'posts'], function() {
    Route::get('/', [\App\Http\Controllers\API\PostController::class, 'all']);
    Route::get('{param}', [\App\Http\Controllers\API\PostController::class, 'getByParameter']);
});
