<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FeedController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//User API
Route::controller(AuthController::class)->group(function () {
    Route::post('/user/register', 'register');
    Route::post('/user/login', 'login');
    Route::post('/user/logout', 'logout');
    Route::post('/user/edit', 'edit');
    Route::get('/user/get', 'checkUser');
});

//Feed API
Route::controller(FeedController::class)->group(function () {
    Route::post('/feed/add', 'add');
    Route::post('/feed/edit', 'edit');
    Route::delete('/feed/delete', 'delete');
    Route::get('/feed', 'getAll');
});