<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $comments =array();
        $itemPerPage = 10;
        $search = $request['search'] ?? "";
        $sortby = $request['sortby'] ?? "ASC";
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        if($search !=""){ 
            $comments = Comment::where('title', 'like',  '%' . $search . '%')->orderBy('created_at', $sortby);
            $comments = $comments->paginate($itemPerPage);
        }else{
            $comments = Comment::paginate($itemPerPage);
        }
        $json['data']=$comments;
        return Response::json($json);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'comment' => 'required|string|unique:comments',
            'user_id' => 'required|integer|exists:users,id',
            'post_id' => 'required|integer|exists:posts,id',
        ]);

        $comment = new Comment([
            'comment' => $request->comment,
            'user_id' => $request->user_id,
            'post_id' => $request->post_id
        ]);
        if($comment->save()){
            return response()->json([
                'message' => 'Successfully created comment!'
            ], 201);
        }else{
            return response()->json(['error'=>'Provide proper details']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
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
     * @param  \App\Comment  $comment
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
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id'=> 'required|integer|exists:comments,id',
        ]);
        Post::find($request->id)->delete(); 
        return response()->json([
            'message' => 'Successfully deleted comments!'
        ], 204);
    }
}
