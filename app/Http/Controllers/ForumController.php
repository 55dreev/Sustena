<?php

// app/Http/Controllers/ForumController.php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends BaseController
{

    
    // app/Http/Controllers/ForumController.php
public function activeUsers(Request $request)
{
    $users = \App\Models\User::orderByDesc('user_id')->paginate(10);

    return response()->json([
        'html'     => view('partials.active_user_items', ['users' => $users])->render(),
        'next_url' => $users->nextPageUrl(),
    ]);
}

    public function __construct(){
        $this->middleware('auth')->except(['index','show']);
    }

    public function index()
{
    $query = Post::with(['user','comments.user'])
        ->withCount(['comments','likes'])
        ->latest();

    if ($q = request('q')) {
        $query->where(function ($qq) use ($q) {
            $qq->where('title', 'like', "%{$q}%")
               ->orWhere('content', 'like', "%{$q}%");
        });
    }

    // Posts stay paginated (for your infinite scroll or pagination)
    $posts = $query->paginate(10)->withQueryString();

    // ⬇️ CHANGE HERE: use paginate (or simplePaginate) instead of limit()->get()
    // Use your actual PK (you said it's `user_id`). Make sure User model has `$primaryKey = 'user_id'`.
    $activeUsers = \App\Models\User::orderByDesc('user_id')
        ->paginate(10);           // or ->simplePaginate(10)
        // ->withQueryString();   // only if you want to preserve ?q=... across user pages

    // (Optional) user stats
    $myStats = ['posts'=>0,'comments'=>0,'likes'=>0];
    if (auth()->check()) {
        $uid = auth()->id();
        $myStats['posts']    = \App\Models\Post::where('user_id', $uid)->count();
        $myStats['comments'] = \App\Models\Comment::where('user_id', $uid)->count();
        $myStats['likes']    = \App\Models\Like::where('user_id', $uid)->count();
    }

    return view('forum', compact('posts', 'activeUsers', 'myStats'));
}

    public function show(Post $post)
    {
        $post->load(['user','comments.user'])->loadCount(['comments','likes']);
        $posts = collect([$post]);

        $activeUsers = User::orderByDesc('user_id')->limit(8)->get();

        return view('forum', compact('posts','activeUsers'));
    }

    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'title'   => ['required','string','max:140'],
            'content' => ['required','string','max:5000'],
        ]);

        $post = Post::create($validated + ['user_id' => Auth::id()]);
        $post->load('user');

        if ($request->wantsJson()) {
            return response()->json([
                'ok'   => true,
                'post' => [
                    'id'         => $post->id,
                    'title'      => $post->title,
                    'content'    => $post->content,
                    'author'     => $post->user->display_name, // <— accessor
                    'created_at' => '0 seconds ago',
                ],
            ]);
        }
        return back()->with('ok','Posted!');
    }

    public function storeComment(Request $request, Post $post)
    {
        $validated = $request->validate(['content' => ['required','string','max:2000']]);

        $comment = $post->comments()->create($validated + ['user_id'=>Auth::id()]);

        if ($request->wantsJson()) {
            return response()->json([
                'ok'=>true,
                'comment'=>[
                    'id'=>$comment->id,
                    'content'=>$comment->content,
                    'author'=>$comment->user->name,
                    'created_at'=>$comment->created_at->diffForHumans(),
                    'count'=>$post->comments()->count(),
                ]
            ]);
        }
        return back();
    }

    public function toggleLike(Post $post)
    {
        $existing = Like::where('post_id',$post->id)->where('user_id',Auth::id())->first();
        if ($existing) { $existing->delete(); $liked=false; }
        else { Like::create(['post_id'=>$post->id,'user_id'=>Auth::id()]); $liked=true; }

        if (request()->wantsJson()) {
            return response()->json(['ok'=>true,'liked'=>$liked,'count'=>$post->likes()->count()]);
        }
        return back();
    }

    public function destroyPost(Post $post)
    {
        $this->authorize('delete',$post);
        $post->delete();
        return back()->with('ok','Deleted');
    }
}


