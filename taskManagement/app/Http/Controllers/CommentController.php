<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

//use App\Models\User;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\StoreCommentRequest;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indextest(Request $request)
    {
        
        //$comments =array();
        //$itemPerPage = 10;
        //$search = $request['search'] ?? "";
        //$sortby = $request['sortby'] ?? "ASC";
        //if($request->has('per_page'))  $itemPerPage=$request->per_page;
        //if($search !=""){ 
        //    $comments = Comment::where('title', 'like',  '%' . $search . '%')->orderBy('created_at', $sortby);
        //    $comments = $comments->paginate($itemPerPage);
        //}else{
        //    $comments = Comment::paginate($itemPerPage);
        //}
        //$json['data']=$comments;
        //return response()->json(['data'=>$comments]);
    }

    public function index(Request $request)
    {
        $comments = auth()->user()->comments;
        var_dump($comments);exit;
        $itemPerPage = 10;
        $search = $request['search'] ?? "";
        $sortby = $request['sortby'] ?? "ASC";
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        if($search !=""){ 
            //$comments = Comment::where('title', 'like',  '%' . $search . '%')->orderBy('created_at', $sortby);
            $comments = auth()->user()->comments::where('title', 'like',  '%' . $search . '%')->orderBy('created_at', $sortby);
            $comments = $comments->paginate($itemPerPage);
        }else{
            $comments = auth()->user()->comments::paginate($itemPerPage);
        }

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }
 
    public function show($id)
    {
        $comment = auth()->user()->comments->find($id);
 
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found '
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $comment->toArray()
        ], 400);
    }
 
    public function store(StoreCommentRequest $request)
    {

        $data = $request->only(['comment', 'post_id', 'user_id']);
        $data['comment'] = $request->comment;
        $data['post_id'] = $request->post_id;
        $data['user_id'] = $request->user_id;
        $data['approval_status'] = 'unapproved';
        $data['published_at'] = date("Y-m-d H:i:s");
        
        $result = Comment::create($data);
        return response()->json([
            'success' => (bool)$result,
            'message' => " Added Successfully",
            'data' => $result->toArray(),
            'status' => 200
        ]);

    }
 
    public function update(StoreCommentRequest $request, $id)
    {
        $comment = auth()->user()->comments->find($id);
 
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'comment not found'
            ], 400);
        }

        $data = $request->all();
        $result = Comment::where('id', $id)->update($data);
        var_dump($result);
        $updated_data = auth()->user()->comments->find($id);
        return response()->json([
            'success' => (bool)$result,
            'message' => "Updated Successfully",
            'updated_data' => $updated_data,
            'status' => 200
        ]);

    }
 
    public function destroy($id)
    {
        $comment = auth()->user()->comments->find($id);
 
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'comment not found'
            ], 400);
        }
 
        if ($comment->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be deleted'
            ], 500);
        }
    }

}
