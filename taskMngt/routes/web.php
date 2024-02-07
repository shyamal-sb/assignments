<?php

//use Illuminate\Http\Request;
//use App\Task;
//use App\Http\Requests\TaskRequest;


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


Route::get('tasks', 'TaskController@index');
Route::get('task/list/{page}/{per_page}', 'TaskController@list');
Route::get('task/{id}', 'TaskController@show');
Route::post('task/insert', 'TaskController@store');
Route::put('task/update/{id}', 'TaskController@update');
Route::delete('task/delete/{id}', 'TaskController@destroy');