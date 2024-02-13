<?php

//ADDITIONAL
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\v1\Post\PostController;
use App\Http\Controllers\v1\comment\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('login', [AuthController::class, 'login'])->middleware('auth:api');
Route::post('register', [AuthController::class, 'register'])->middleware('auth:api');

//Post section
//Route::apiResource('/post', 'PostController')->middleware('auth:api');
Route::post('post/insert', [PostController::class, 'store'])->middleware('auth:api');
Route::get('posts', [PostController::class, 'index'])->middleware('auth:api');
Route::get('allposts', [PostController::class, 'showAll'])->middleware('auth:api');
Route::get('post/{id}', [PostController::class, 'show'])->middleware('auth:api');
Route::put('post/update', [PostController::class, 'update'])->middleware('auth:api');
Route::delete('post/delete', [PostController::class, 'destroy'])->middleware('auth:api');

//Comment section
//Route::apiResource('/comment', 'CommentController')->middleware('auth:api');

Route::post('comment/insert', [CommentController::class, 'store']);
Route::get('comments', [CommentController::class, 'index']);
Route::get('allcomments', [CommentController::class, 'showAll'])->middleware('auth:api');
Route::get('comment/{id}', [CommentController::class, 'show'])->middleware('auth:api');
Route::put('comment/update', [CommentController::class, 'update'])->middleware('auth:api');
Route::delete('comment/delete', [CommentController::class, 'destroy'])->middleware('auth:api');


//Route::post('comment/insert', [CommentController::class, 'store'])->middleware('auth:api');
//Route::get('comments', [CommentController::class, 'index'])->middleware('auth:api');
//Route::get('allcomments', [CommentController::class, 'showAll'])->middleware('auth:api');
//Route::get('comment/{id}', [CommentController::class, 'show'])->middleware('auth:api');
//Route::put('comment/update', [CommentController::class, 'update'])->middleware('auth:api');
//Route::delete('comment/delete', [CommentController::class, 'destroy'])->middleware('auth:api');
