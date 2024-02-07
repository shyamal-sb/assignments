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

        return response()->json([
            'data' => $comments
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
