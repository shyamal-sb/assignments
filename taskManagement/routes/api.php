<?php

use Illuminate\Http\Request;

//ADDITIONAL
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Routing to user
Route::get('post/showComment', [PostController::class, 'showComment']);
  

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']); 


    Route::group(['middleware' => 'auth:api'], function() {

        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);

        //Post APIs
        Route::post('post/insert', [PostController::class, 'store']);
        Route::get('posts', [PostController::class, 'index']);
        Route::get('allposts', [PostController::class, 'showAll']);
        Route::get('post/{id}', [PostController::class, 'show']);
        Route::put('post/update', [PostController::class, 'update']);
        Route::delete('post/delete', [PostController::class, 'destroy']);        
        Route::get('post/showComment', [PostController::class, 'showComment']);

        //Post APIs
        Route::post('comment/insert', [PostController::class, 'store']);
        Route::get('comments', [PostController::class, 'index']);
        Route::get('allcomments', [PostController::class, 'showAll']);
        Route::get('comment/{id}', [PostController::class, 'show']);
        Route::put('comment/update', [PostController::class, 'update']);
        Route::delete('comment/delete', [PostController::class, 'destroy']);
    });

});