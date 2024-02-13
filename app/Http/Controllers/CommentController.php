<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

//use App\Models\User;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreCommentRequest;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

use App\Http\Requests\StorePostRequest;


//unluQqueryBuilder
use Unlu\Laravel\Api\QueryBuilder;
//use App\Http\Helpers\QueryBuilder\BlogQueryBuilder;
use App\Http\Helpers\QueryBuilder\AllQueryBuilder;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $queryBuilder = new AllQueryBuilder(new Comment, $request);
        $records = $queryBuilder->build()->get();
        $count = $queryBuilder->build()->count();
        $result = [
            'count' => $count,
            'records' => $records,
        ];
        return response()->json([$result], Response::HTTP_OK);
        /*
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
        ], Response::HTTP_OK);
        */
    }
 
    public function show(Request $request, $id)
    {
        
        $comment = auth()->user()->comments->find($id);
 
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found '
            ], Response::HTTP_NOT_FOUND);
        }
 
        return response()->json([
            'success' => true,
            'data' => $comment->toArray()
        ], Response::HTTP_OK);
        
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
            'data' => $result->toArray()
        ], Response::HTTP_CREATED);

    }
 
    public function update(StoreCommentRequest $request, $id)
    {
        $comment = auth()->user()->comments->find($id);
 
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        //$data = $request->all();
        $data = $request->only(['comment', 'post_id', 'user_id']);
        $result = Comment::where('id', $id)->update($data);
        //var_dump($result);
        $updated_data = auth()->user()->comments->find($id);
        return response()->json([
            'success' => (bool)$result,
            'message' => "Updated Successfully",
            'updated_data' => $updated_data
        ], Response::HTTP_OK);

    }
 
    public function destroy(Request $request)
    {
        $id = $request->id;
        $comment = auth()->user()->comments->find($id);
 
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'comment not found'
            ], Response::HTTP_NO_CONTENT);
        }
 
        if ($comment->delete()) {
            return response()->json([
                'success' => true,
                'message' => "Deleted Successfully"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be deleted'
            ], Response::HTTP_NOT_IMPLEMENTED); //500
        }
    }


    //Getting comment by User
    public function getCommentsByUser(Request $request){

        $users = User::with(['comments' => fn($query) => $query->where('comment', 'like', '%test%')])
            ->whereHas('comments', fn ($query) => 
            $query->where('comment', 'like', '%PHP%')
        )->get();

        return $users;

    }

    //test query

    public function getData(Request $request){

        $queryBuilder = new AllQueryBuilder(new Comment, $request);
        $records = $queryBuilder->build()->get();
        $count = $queryBuilder->build()->count();
        $result = [
            'count' => $count,
            'records' => $records,
        ];
        return response()->json([$result], Response::HTTP_OK);
        
        /*
        //var_dump($request->comment); exit('inside the getdata');
        //$queryBuilder = new QueryBuilder(new User, $request);
        //return $queryBuilder;
        //exit();
        $comm = Comment::with(['commentBy'])
        ->where('user_id', 1)
        //->select(['user_id', 'post_id', 'published_at', 'approval_status'])
        ->where('post_id', 5)
                    ->where('comment', 'like', '%test%')
                    //->where('published_at', '!=', NULL)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->paginate(5);
        //return $comm;
        //var_dump($comm); exit;
        return response()->json([
            'success' => true,
            'data' => $comm
        ], Response::HTTP_OK);

        */
    }

}
