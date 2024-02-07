<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validator;
use Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts =array();
        $itemPerPage = 10;
        $search = $request['search'] ?? "";
        $sortby = $request['sortby'] ?? "ASC";
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        if($search !=""){ 
            $posts = Post::where('title', 'like',  '%' . $search . '%')->orderBy('created_at', $sortby);
            $posts = $posts->paginate($itemPerPage);
        }else{
            $posts = Post::paginate($itemPerPage);
        }
        $json['data']=$posts;
        return Response::json($json);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @param  [string] title
     * @param  [string] content
     * @param  [integer] user_id 
     * @param  [datetime] published_at
     * @return [string] message
    */
    public function store(Request $request)
    {
        //$chk_token = $request->bearerToken();
        //if(empty($chk_token)){
        //    return response()->json(['error'=>'Login with correct credentials']);
        //}

        $request->validate([
            'title' => 'required|string|min:3|max:255|unique:posts',
            'content' => 'required|string',
            'user_id' => 'required|integer|exists:posts,id',
        ]);

        $post = new Post([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user_id
        ]);
        if($post->save()){
            return response()->json([
                'message' => 'Successfully created post!'
            ], 201);
        }else{
            return response()->json(['error'=>'Provide proper details']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::find($id);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        return Post::all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255|unique:posts',
            'content' => 'required|string',
            'user_id'=>'required|integer',
        ]);
        
        $post = post ::find($id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user_id              
        ]);
        

        //if($post->save()){
        if($post){
            return response()->json([
                'message' => 'Successfully updated post!'
            ], 201);
        }else{
            return response()->json(['error'=>'Provide proper details']);
        }

        //$task = Task::find($id);
        ////$data = $request->all();
        //if($task){
        //    $task->update($request->all());
        //    //$resp['code']=200;
        //    //$resp['error']=false;
        //    $resp['data']=$task;
        //    return Response::json($resp);
        //}else{
        //    $resp['code']=200;
        //    $resp['error']=true;
        //    $resp['data']="";
        //    return Response::json($resp);
        //}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //  $post = Post::findOrFail($id);
        //  $post->delete();
        //  return 204;

        $post = Post::find($id);
        if($post){
            Post::find($id)->delete(); 
            $resp['code']=204;
            $resp['error']=false;    
            $resp['message']='Post is deleted successfully.';
            $resp['data']='';
            return Response::json($resp);
        }else{
            $resp['code']=200;
            $resp['error']=true;
            $resp['message']='Id not found.';
            $resp['data']="";
            return Response::json($resp);
        }
    }
}
