<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//using additional classes
use App\Task;
use App\Http\Requests\TaskRequest;

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


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function() {
      Route::get('logout', [AuthController::class, 'logout']);
      Route::get('user', [AuthController::class, 'user']);
    });
});


//auth group
/*
Route::post('/logout', function(){

    if (Auth::check()) {
        Auth::user()->AauthAcessToken()->delete();
    }

    return response()->json([

        'status'    => 1,
        'message'   => 'User Logout',

    ], 200);
});
*/

//  $router->group(['middleware' => 'auth:api'], function () use ($router) {
//      //Route::get('me/logout', 'UserController@logout');
//      Route::post('me/logout', 'Auth\ApiAuthController@logout')->name('logout.api');
//  });
//  
//  
//  Route::group(['middleware' => ['cors', 'json.response']], function () {
//      // public routes
//      Route::post('/login', 'Auth\ApiAuthController@login')->name('login.api');
//      Route::post('/register','Auth\ApiAuthController@register')->name('register.api');
//      Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');
//  });


//My Custom API for Tasks in case of API

Route::get('tasks', function(Request $request) {
    //return Task::all(); var_dump($request); exit('EENNDD');
    $per_page=10;
    if($request->has('per_page'))  $per_page=$request->per_page;
    $resp['code']=200;
    $resp['error']=false;
    $tasks = Task::paginate($per_page);
    $resp['data']=$tasks;
    return Response::json($resp);
});

Route::get('task/listdata/', 'TaskController@listdata');

Route::get('task/list', function(Request $request) {
    $per_page=10;
    if($request->has('per_page'))  $per_page=$request->per_page;
    // Make sure column names are correct
    $data = Task::orderBy('completed', 'ASC')->orderBy('created_at', 'DESC')->get();

    $resp['code']=200;
    $resp['error']=false;
    $resp['message']='All tasks';
    //$tasks = Task::paginate($per_page);
    //$resp['data']=$tasks;
    $resp['data']=$data;
    return Response::json($resp);
});
 
Route::get('task/{id}', function($id) {
    $tasks = Task::find($id);
    if($tasks){
        $recdata = 'task found.';
    }else{
        $recdata = 'No task found.';
    }
    $resp['code']=200;
    $resp['error']=false;
    $resp['message']=$recdata;
    $resp['data']=$tasks;
    return Response::json($resp);
});

//Route::post('task/add', function(Request $request) {
Route::post('task/add', function(TaskRequest $request) {

    //dd($request);
    $data = $request->all();
    $resdata ='';
    $resdata = Task::create($request->all());
    $resp['code']=200;
    $resp['error']=false;
    $resp['data']=$resdata;
    return Response::json($resp);

});

Route::put('task/update/{id}', function(TaskRequest $request, $id) {

    //$task = Task::findOrFail($id);
    $task = Task::find($id);
    //$data = $request->all();
    if($task){
        $task->update($request->all());
        $resp['code']=200;
        $resp['error']=false;
        $resp['data']=$task;
        return Response::json($resp);
    }else{
        $resp['code']=200;
        $resp['error']=true;
        $resp['data']="";
        return Response::json($resp);
    }
 
});

Route::delete('task/delete/{id}', function($id) {
    $task = Task::find($id);
    if($task){
        Task::find($id)->delete(); 
        $resp['code']=204;
        $resp['error']=false;    
        $resp['message']='Task is deleted successfully.';
        $resp['data']='';
        return Response::json($resp);
    }else{
        $resp['code']=200;
        $resp['error']=true;
        $resp['message']='Id not found.';
        $resp['data']="";
        return Response::json($resp);
    }
});
