<?php

namespace App\Http\Controllers\v1\post;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;


//unluQqueryBuilder
use Unlu\Laravel\Api\QueryBuilder;
//use App\Http\Helpers\QueryBuilder\BlogQueryBuilder;
use App\Http\Helpers\QueryBuilder\AllQueryBuilder;

//calling collection
use Illuminate\Database\Eloquent\Builder;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;


class PostController extends Controller
{
    
    public function index(Request $request)
    {
       //dd('test');
        $queryBuilder = new AllQueryBuilder(new Post, $request);
        //  $queryBuilder = $queryBuilder->withRelation(['user']);
        $records = $queryBuilder->build()->get();
        $count = $queryBuilder->build()->count();
        $result = [
            'count' => $count,
            'records' => $records,
        ];
        return response()->json([$result], Response::HTTP_OK);

    }
 
    public function getPostedBy(Request $request, $user_id){

        $queryBuilder = new AllQueryBuilder(new Post, $request);
        //  $queryBuilder = $queryBuilder->withRelation(['user']);
        $records = $queryBuilder->build()->get();
        $count = $queryBuilder->build()->count();
        $result = [
            'count' => $count,
            'records' => $records,
        ];
        return response()->json([$result], Response::HTTP_OK);

    }

    public function getCommentList(Request $request, $user_id){
        
        $queryBuilder = new AllQueryBuilder(new Comment, $request);
        $queryBuilder = $queryBuilder->withRelation(['user']);
        $records = $queryBuilder->build()->get();
        $count = $queryBuilder->build()->count();
        $result = [
            'count' => $count,
            'records' => $records,
        ];
        return response()->json([$result], Response::HTTP_OK);
    }

    public function getCommentedBy(Request $request, $user_id){
        $queryBuilder = new AllQueryBuilder(new Post, $request);
        //  $queryBuilder = $queryBuilder->withRelation(['user']);
        $records = $queryBuilder->build()->get();
        $count = $queryBuilder->build()->count();
        $result = [
            'count' => $count,
            'records' => $records,
        ];
        return response()->json([$result], Response::HTTP_OK);
    }

    //Blog post added by
    public function getPostByUserId(Request $request, $user_id){
        
        $queryBuilder = new AllQueryBuilder(new Post, $request);
        //  $queryBuilder = $queryBuilder->withRelation(['user']);
        $records = $queryBuilder->build()->get();
        $count = $queryBuilder->build()->count();
        $result = [
            'count' => $count,
            'records' => $records,
        ];
        return response()->json([$result], Response::HTTP_OK);
        
    }

    public function allpostcomments(Request $request, $post_id){
        $queryBuilder = new AllQueryBuilder(new Post, $request);
        //  $queryBuilder = $queryBuilder->withRelation(['user']);
        $records = $queryBuilder->build()->get();
        $count = $queryBuilder->build()->count();
        $result = [
            'count' => $count,
            'records' => $records,
        ];
        return response()->json([$result], Response::HTTP_OK);    
    }

    public function show($id)
    {
        $post = auth()->user()->posts->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], Response::HTTP_OK);
        }
 
        return response()->json([
            'success' => true,
            'data' => $post->toArray()
        ], Response::HTTP_OK);
    }
 
    public function store(StorePostRequest $request)
    {
        $data = $request->only(['title', 'content', 'user_id']);
        $data['title'] = $request->title;
        $data['content'] = $request->content;
        $data['user_id'] = $request->user_id;
        $data['published_at'] = date("Y-m-d H:i:s");
        
        $result = Post::create($data);
        return response()->json([
            'success' => (bool)$result,
            'message' => "Added Successfully",
            'data' => $result->toArray()
        ], Response::HTTP_CREATED);
    }
 
    public function update(StorePostRequest $request, $id)
    {
        $post = auth()->user()->posts->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found.'
            ], Response::HTTP_NO_CONTENT);
        }

        //$data = $request->all();
        $data = $request->only(['title', 'content', 'user_id']);
        $result = Post::where('id', $id)->update($data);
        $updated_data = Post::find($id);
        return response()->json([
            'success' => (bool)$result,
            'message' => "Updated Successfully",
            'updated_data' => $updated_data,
            'status' => 200
        ], Response::HTTP_OK);
        
    }
 
    public function destroy(Request $request)
    {
        $id = $request->id;
        $post = auth()->user()->posts->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], Response::HTTP_NO_CONTENT);
        }
 
        if ($post->delete()) {
            return response()->json([
                'success' => true,
                'message' => "Deleted Successfully"
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be deleted'
            ], Response::HTTP_OK);
        }
    }

    
    
}
