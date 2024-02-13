<?php

use Illuminate\Http\Request;

//ADDITIONAL
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\v1\Post\PostController;
use App\Http\Controllers\v1\Comment\CommentController;


Route::namespace('v1\BlogApi')->group(function () {
    
    //Route::get('dashboard/water-saving', 'DashboardController@waterSavingData');
    

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']); 
    
    
        Route::group(['middleware' => 'auth:api'], function() {
    
            Route::get('logout', [AuthController::class, 'logout']);
            Route::get('user', [AuthController::class, 'user']);
    
        });
    
    
    });

    Route::group(['prefix' => 'post'], function () {
        Route::group(['middleware' => 'auth:api'], function() {

            Route::post('insert', [CommentController::class, 'store']);
            Route::get('getall', [CommentController::class, 'index']);
            Route::get('getposts', [CommentController::class, 'showAll']);
            Route::get('allpostcomments/{post_id}', [PostController::class, 'allpostcomments']);
            Route::get('{id}', [CommentController::class, 'show']);
            Route::put('update/{id}', [CommentController::class, 'update']);
            Route::delete('delete', [CommentController::class, 'destroy']);
            //testing            
            Route::get('post/{id}', [PostController::class, 'show']);
            Route::put('post/update/{id}', [PostController::class, 'update']);
            Route::delete('post/delete', [PostController::class, 'destroy']);
            Route::get('post/showComment', [PostController::class, 'showComment']);
            //Route::get('post/postedby/{$user_id}', [PostController::class, 'getPostByUserId']);
      
            Route::get('post/commentedby/{user_id}', [PostController::class, 'getCommentedBy']);
            Route::get('post/postedby/{user_id}', [PostController::class, 'getPostedBy']);
            Route::get('post/getCommentList/{user_id}', [PostController::class, 'getCommentList']);
        
        });
    });

    //post/getPostedBy/1
    //Comments APIs
    Route::group(['prefix' => 'comment'], function () {
        Route::group(['middleware' => 'auth:api'], function() {

            Route::post('insert', [CommentController::class, 'store']);
            Route::get('getall', [CommentController::class, 'index']);
            Route::get('allcomments', [CommentController::class, 'showAll']);
            Route::get('{id}', [CommentController::class, 'show']);
            Route::put('update/{id}', [CommentController::class, 'update']);
            Route::delete('delete', [CommentController::class, 'destroy']);
            //testing
            Route::get('commentdata/getData', [CommentController::class, 'getData']);
        
        });
    });
    
});