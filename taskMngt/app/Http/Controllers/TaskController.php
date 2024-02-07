<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use Illuminate\Http\JsonResponse;

use App\Task;

use App\Http\Requests\TaskRequest;

use Response;



class TaskController extends Controller
{
    //
    public function index()
    {
  
        $tasks =array();
        $itemPerPage = 10;
        $search = $request['search'] ?? "";
        $sortby = $request['sortby'] ?? "ASC";
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        if($search !=""){ //$tasks = Task::where('title', 'like',  '%' . $search . '%')->get(); //$tasks = Task::where('title', 'like',  '%' . $search . '%')->orderBy('id','DESC');
            $tasks = Task::where('title', 'like',  '%' . $search . '%')->orderBy('created_at', $sortby);
            $tasks = $tasks->paginate($itemPerPage);
        }else{
            $tasks = Task::paginate($itemPerPage);
        }
        //$json['task'] = $tasks;
        $json['data']=$tasks;
        return Response::json($json);

    }
 

    public function listdata(Request $request){
        $tasks =array();
        $itemPerPage = 10;
        $search = $request['search'] ?? "";
        $sortby = $request['sortby'] ?? "ASC";
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        if($search !=""){ //$tasks = Task::where('title', 'like',  '%' . $search . '%')->get(); //$tasks = Task::where('title', 'like',  '%' . $search . '%')->orderBy('id','DESC');
            $tasks = Task::where('title', 'like',  '%' . $search . '%')->orderBy('created_at', $sortby);
            $tasks = $tasks->paginate($itemPerPage);
            //$tasks = Task::where('completed', '=',  $search)->get();
        }else{
            $tasks = Task::paginate($itemPerPage);
        }

        //$json['task'] = $tasks;
        $json['data']=$tasks;
        return Response::json($json);

    }
    
    public function list(Request $request){
        $tasks =array();
        $itemPerPage = 10;
        $search = $request['search'] ?? "";
        $sortby = $request['sortby'] ?? "ASC";
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        if($search !=""){ //$tasks = Task::where('title', 'like',  '%' . $search . '%')->get(); //$tasks = Task::where('title', 'like',  '%' . $search . '%')->orderBy('id','DESC');
            $tasks = Task::where('title', 'like',  '%' . $search . '%')->orderBy('created_at', $sortby);
            $tasks = $tasks->paginate($itemPerPage);
            //$tasks = Task::where('completed', '=',  $search)->get();
        }else{
            $tasks = Task::paginate($itemPerPage);
        }

        //$json['task'] = $tasks;
        $json['data']=$tasks;
        return Response::json($json);
    }
    
    public function show($id)
    {
        return Task::find($id);
    }

    //public function store(Request $request)
    public function store(TaskRequest $request)
    {
        //var_dump($request); exit('end in route');
        //return Task::create($request->all());
    
        $resdata = Task::create($request->all());
        $resp['code']=200;
        $resp['error']=false;
        $resp['data']=$resdata;
        return Response::json($resp);
    }

    public function update(TaskRequest $request, $id)
    {
        //$task = Task::findOrFail($id);
        //$task->update($request->all());
        //return $task;
        $task = Task::find($id);
        //$data = $request->all();
        if($task){
            $task->update($request->all());
            //$resp['code']=200;
            //$resp['error']=false;
            $resp['data']=$task;
            return Response::json($resp);
        }else{
            $resp['code']=200;
            $resp['error']=true;
            $resp['data']="";
            return Response::json($resp);
        }
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return 204;
    }
}
