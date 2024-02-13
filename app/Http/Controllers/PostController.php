<?php

namespace App\Http\Controllers;
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
        //dd('123');
        $queryBuilder = new AllQueryBuilder(new Post, $request);
        //  $queryBuilder = $queryBuilder->withRelation(['user']);
        $records = $queryBuilder->build()->get();
        $count = $queryBuilder->build()->count();
        $result = [
            'count' => $count,
            'records' => $records,
        ];
        return response()->json([$result], Response::HTTP_OK);

        /*
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
        ], Response::HTTP_OK );
        
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

        */
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

        /*
        $itemPerPage = 10;
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        $sortby = $request->sortBy ?? "ASC";
        $title = $request->title ?? "";
        $content = $request->content ?? "";
        $posts = Post::
        //->select(['user_id', 'post_id', 'published_at', 'approval_status'])
        with(['postedBy'])
        ->where('user_id', $user_id)        
        //->where('post_id', $request->post_id)
        ->when($title, function ($qry) use ($title) {
            return $qry->where('title', 'like', "% $title %");
        })
        ->when($content, function ($qry) use ($content) {
            return $qry->where('title', 'like', "% $content %");
        })                    
        //->Orwhere('content', 'like', "%$request->content%")
        //->where('published_at', '!=', NULL)
        ->orderBy('id', $sortby)
        ->get()
        ->paginate($itemPerPage);
        //return $comm;
        //var_dump($comm); exit;
        return response()->json([
            'success' => true,
            'data' => $posts
        ], Response::HTTP_OK);

        */
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

        /*
        $itemPerPage = 10;
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        $sortby = $request->sortBy ?? "ASC";
        $title = $request->title ?? "";
        $content = $request->content ?? "";
        $posts = Post::
        //->select(['user_id', 'post_id', 'published_at', 'approval_status'])
        with(['commentedBy'])
        ->where('user_id', $user_id)        
        //->where('post_id', $request->post_id)
        ->when($title, function ($qry) use ($title) {
            return $qry->where('title', 'like', "% $title %");
        })
        ->when($content, function ($qry) use ($content) {
            return $qry->where('title', 'like', "% $content %");
        })                    
        //->Orwhere('content', 'like', "%$request->content%")
        //->where('published_at', '!=', NULL)
        ->orderBy('id', $sortby)
        ->get()
        ->paginate($itemPerPage);
        //return $comm;
        //var_dump($comm); exit;
        return response()->json([
            'success' => true,
            'data' => $posts
        ], Response::HTTP_OK);
        */
    }

    //Blog post added by
    public function getPostByUserId(Request $request, $user_id){
        exit('inside the getPostedBy');
        $sortby = $request->sortBy ?? "ASC";
        $title = $request->title ?? "";
        $content = $request->content ?? "";
        $posts = Post::
        //->select(['user_id', 'post_id', 'published_at', 'approval_status'])
        with(['postedBy'])
        ->where('user_id', $user_id)        
        //->where('post_id', $request->post_id)
        ->when($title, function ($qry) use ($title) {
            return $qry->where('title', 'like', "% $title %");
        })
        ->when($content, function ($qry) use ($content) {
            return $qry->where('title', 'like', "% $content %");
        })                    
        //->Orwhere('content', 'like', "%$request->content%")
        //->where('published_at', '!=', NULL)
        ->orderBy('id', $sortby)
        ->get()
        ->paginate(5);
        //return $comm;
        //var_dump($comm); exit;
        return response()->json([
            'success' => true,
            'data' => $posts
        ], Response::HTTP_OK);
    }

    public function allpostcomments(Request $request, $post_id){
        $itemPerPage = 10;
        //$items = Comment::all();
        //$collection = (new Collection($items))->paginate($itemPerPage);
        
        $search = $request['search'] ?? "";
        $sortby = $request['sortby'] ?? "ASC";
        if($request->has('per_page'))  $itemPerPage=$request->per_page;

        /*
        $users = QueryBuilder::for(User::class)
        ->allowedFilters(['name', 'email'])
        ->paginate()
        ->appends(request()->query());

        */
    
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
        ], Response::HTTP_OK);
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
