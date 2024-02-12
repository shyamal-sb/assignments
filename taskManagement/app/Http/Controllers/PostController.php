<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use Illuminate\Database\Eloquent\Builder;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;


//unluQqueryBuilder
//use Unlu\Laravel\Api\QueryBuilder;
//use App\Http\Helpers\QueryBuilder\BlogQueryBuilder;

//calling collection
use App\Support\Collection;


class PostController extends Controller
{
    
    public function index(Request $request)
    {
        $posts = auth()->user()->posts;
        $itemPerPage = 10;        
        $search = $request['search'] ?? "";
        $title = $request->title ?? "";
        $content = $request->content ?? "";
        $sortby = $request['sortby'] ?? "ASC";
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        $posts = Post::query()
          ->when($request->title, fn($q, $title)=>$q->where('title' , 'like', "%{$title}%"))
          ->when($request->content, fn($q, $content)=>$q->Orwhere('content' , 'like', "%{$content}%"))
          //->where('title' , 'like', "%{$title}%")
          //->Orwhere('content' , 'like', "%{$content}%")
          //->where('published_at', '!=', 'NULL')
          ->whereNotNull('published_at')
          ->orderBy('created_at', $sortby)
          ->paginate($itemPerPage)
          ->appends($request->all());
          //->withQueryString();
   
        //$posts = $posts->paginate($itemPerPage);
        //$posts->when($request->title, fn($q, $title)=>$q->where('title' , 'like', "%{$title}%"))
        //->when($request->content, fn($q, $content)=>$q->where('content' , 'like', "%{$content}%"))
        //->where('published_at', '!=', 'NULL')
        ////->whereNotNull('published_at')
        ////->orderBy('created_at', $sortby)
        //->paginate($itemPerPage)
        //->appends($request->all());


        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
        
        //$post = auth()->user()->posts->find(3);
        //$comments = auth()->user()->comments()->find(3);
 
        //return response()->json([
        //    'success' => true,
        //    'data' => $posts
        //]);

        //$queryBuilder = new BlogQueryBuilder(new Post, $request);
        //$records = $queryBuilder->build()->where('name', $request>title);
        //$result = [
        //    'records' => $records,
        //];
        //return response()->json(
        //    [
        //        'success' => true,
        //        'data' => $result
        //    ]
        //);


    }
 

    public function allpostcomments(Request $request, $post_id){
        $itemPerPage = 10;
        //$items = Comment::all();
        //$collection = (new Collection($items))->paginate($itemPerPage);
        
        $search = $request['search'] ?? "";
        $sortby = $request['sortby'] ?? "ASC";
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        //$comments = Comment::paginate(3);
        //var_dump($request); var_dump($post_id); var_dump($request->title); var_dump($request->comment);
        $comments = Comment::query()
        ->when($request->comment, fn($q, $comment)=>$q->where('title' , 'like', "%{$comment}%"))
        ->where('post_id', '=', $post_id)
        //->where('approval_status', '=', 'approved')
        ->orderBy('created_at', $sortby)
        ->paginate($itemPerPage)
        ->appends($request->all());
        //->withQueryString();
        
        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
        //exit;
        //$queryBuilder = new BlogQueryBuilder(new Post, $request);
        //$records = $queryBuilder->build()->get();
        //$count = $queryBuilder->build()->count();
        //$result = [
        //    'count' => $count,
        //    'records' => $records
        //];
        //return response()->json($result);

    
    }

    public function show($id)
    {
        $post = auth()->user()->posts->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found '
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $post->toArray()
        ], 400);
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
            'data' => $result->toArray(),
            'status' => 200
        ]);
    }
 
    public function update(StorePostRequest $request, $id)
    {
        $post = auth()->user()->posts->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'post not found'
            ], 400);
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
        ]);
        
    }
 
    public function destroy(Request $request)
    {
        $id = $request->id;
        $post = auth()->user()->posts->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }
 
        if ($post->delete()) {
            return response()->json([
                'success' => true,
                'message' => "Deleted Successfully"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be deleted'
            ], 500);
        }
    }

}
