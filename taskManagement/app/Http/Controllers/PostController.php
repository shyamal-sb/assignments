<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;
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
    public function update(Request $request)
    {
        $post = Post::find($request->id);
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string',
            'user_id'=> 'required|integer|exists:users,id',
            'id'=> 'required|integer|exists:posts,id',
        ]);
        
        if($post){
            $post = Post ::find($request->id)->update([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => $request->user_id              
            ]);
            
            if($post){
                return response()->json([
                    'message' => 'Successfully updated post!',
                    'data'=> Post ::find($request->id)
                ], 201);
            }else{
                return response()->json(['error'=>'Provide proper details']);
            }
        }else{
            return response()->json(['error'=>'Provide proper details']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id'=> 'required|integer|exists:posts,id',
        ]);
        Post::find($request->id)->delete(); 
        return response()->json([
            'message' => 'Successfully deleted post!'
        ], 204);
    }


    //Show comments by id
    public function showComment(Request $request)
    {
       
        //$comments = Comment::find($request->post_id);
        $request->validate([
            'user_id'=> 'required|integer|exists:users,id',
            'post_id'=> 'required|integer|exists:posts,id|exists:comments,id',
        ]);
        
        $comments = Comment::where('post_id' ,'=', $request->post_id )
        ->where('user_id' ,'=', $request->user_id )->get();

        return response()->json([
                    'message' => 'data!',
                    'data'=> $comments
                ], 200);

    }

}
