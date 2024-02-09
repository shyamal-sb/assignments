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

class PostController extends Controller
{
    
    public function index()
    {
        $posts = auth()->user()->posts;
        //$post = auth()->user()->posts->find(3);
        //$comments = auth()->user()->comments()->find(3);
 
        return response()->json([
            'success' => true,
            'data' => $posts
        ]);

    }
 

    public function allpostcomments(Request $request){
        $comments = Comment::paginate(3);
        //post/allpostcomments
        var_dump($comments); 
        //exit("Ends here for all comments");
        //var_dump($comments);
        $itemPerPage = 10;
        $search = $request['search'] ?? "";
        $sortby = $request['sortby'] ?? "ASC";
        if($request->has('per_page'))  $itemPerPage=$request->per_page;
        if($search !=""){ 
            //$comments = auth()->user()->comments->where('title', 'like',  '%' . $search . '%')->orderBy('created_at', $sortby);
            $comments = auth()->user()->comments::where('title', 'like',  '%' . $search . '%')->orderBy('created_at', $sortby);
            $comments = $comments->paginate($itemPerPage);
        }else{
            //$comments = auth()->user()->comments->where('user_id', Auth::id())->paginate($itemPerPage);
            $comments = auth()->user()->comments::paginate($itemPerPage);
            //$comments = $comments->paginate($itemPerPage);
        }

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
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
        $data = $request->all();
        $data['title'] = $request->title;
        $data['content'] = $request->content;
        $data['user_id'] = $request->user_id;
        $data['published_at'] = date("Y-m-d H:i:s");
        
        $result = Comment::create($data);
        return response()->json([
            'success' => (bool)$result,
            'message' => "Action Added Successfully",
            'data' => $result->toArray(),
            'status' => 200
        ]);
    }
 
    public function update(StorePostRequest $request, $id)
    {

        $data = $request->all();
        $result = Post::where('_id', $id)->update($data);
        return response()->json([
            'success' => (bool)$result,
            'message' => "Updated Successfully",
            'status' => 200
        ]);
        
    }
 
    public function destroy($id)
    {
        $post = auth()->user()->posts->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }
 
        if ($post->delete()) {
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
