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

        ////Post APIs
        //Route::post('post/insert', [PostController::class, 'store']);
        //Route::get('posts', [PostController::class, 'index']);
        //Route::get('allposts', [PostController::class, 'showAll']);
        //Route::get('post/{id}', [PostController::class, 'show']);
        //Route::put('post/update', [PostController::class, 'update']);
        //Route::delete('post/delete', [PostController::class, 'destroy']);        
        //Route::get('post/showComment', [PostController::class, 'showComment']);
        ////Post APIs
        //Route::post('comment/insert', [CommentController::class, 'store']);
        //Route::get('comments', [CommentController::class, 'index']);
        //Route::get('allcomments', [CommentController::class, 'showAll']);
        //Route::get('comment/{id}', [CommentController::class, 'show']);
        //Route::put('comment/update', [CommentController::class, 'update']);
        //Route::delete('comment/delete', [CommentController::class, 'destroy']);

    });


});

//Route::apiResource('/comment', 'CommentController')->middleware('auth:api');


//Route::middleware('auth:api')->group(function () {
//    Route::resource('posts', PostController::class);
//});

//Route::get('posts', [PostController::class, 'index'])->middleware('auth:api');

//Post APIs
Route::post('post/insert', [PostController::class, 'store'])->middleware('auth:api');
Route::get('posts', [PostController::class, 'index'])->middleware('auth:api');
Route::get('allposts', [PostController::class, 'showAll'])->middleware('auth:api');
Route::get('post/allpostcomments/{post_id}', [PostController::class, 'allpostcomments'])->middleware('auth:api');
Route::get('post/{id}', [PostController::class, 'show'])->middleware('auth:api');
Route::put('post/update/{id}', [PostController::class, 'update'])->middleware('auth:api');
Route::delete('post/delete', [PostController::class, 'destroy'])->middleware('auth:api');
Route::get('post/showComment', [PostController::class, 'showComment'])->middleware('auth:api');
//Route::get('post/postedby/{$user_id}', [PostController::class, 'getPostByUserId'])->middleware('auth:api');

Route::get('post/commentedby/{user_id}', [PostController::class, 'getCommentedBy'])->middleware('auth:api');
Route::get('post/postedby/{user_id}', [PostController::class, 'getPostedBy'])->middleware('auth:api');

//post/getPostedBy/1
//Comments APIs
Route::post('comment/insert', [CommentController::class, 'store'])->middleware('auth:api');
Route::get('comments', [CommentController::class, 'index'])->middleware('auth:api');
Route::get('allcomments', [CommentController::class, 'showAll'])->middleware('auth:api');
Route::get('comment/{id}', [CommentController::class, 'show'])->middleware('auth:api');
Route::put('comment/update/{id}', [CommentController::class, 'update'])->middleware('auth:api');
Route::delete('comment/delete', [CommentController::class, 'destroy'])->middleware('auth:api');
//testing
Route::get('commentdata/getData', [CommentController::class, 'getData'])->middleware('auth:api');