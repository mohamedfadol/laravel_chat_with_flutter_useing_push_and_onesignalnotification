<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatMessageController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->as('auth.')->group(function(){
   Route::post('login',[AuthController::class, 'login'])->name('login'); 
   Route::post('register',[AuthController::class, 'register'])->name('register'); 
   Route::post('login-with-token',[AuthController::class, 'loginWithToken'])
    ->middleware('auth:sanctum')->name('login_with_token'); 
   Route::get('logout',[AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout'); 
});


Route::middleware('auth:sanctum')->group(function (){
    Route::apiResource('chat', ChatController::class)->only(['index','store','show']);
    Route::apiResource('chat_message', ChatMessageController::class)->only(['index','store']);
    Route::apiResource('user', UserController::class)->only(['index']);

});
