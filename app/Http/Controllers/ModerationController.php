<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Schema;

class ModerationController extends Controller
{
    public function index(Request $request)
    {
        return view('moderation'); // you already have this blade
    }

    public function comments(Request $request)
{
    $status = $request->string('status')->toString(); // All|Pending|Approved|Flagged
    $q      = trim($request->string('q')->toString());
    $per    = (int) $request->integer('per', 10);

    $query = Comment::with([
        // match your actual columns
        'user:user_id,username,email',
        'post:id,title',
    ])->latest('comments.created_at');

    if ($status && $status !== 'All') {
        $query->where('status', $status);
    }

    if ($q !== '') {
        $query->where(function ($w) use ($q) {
            $w->where('content', 'like', "%{$q}%")
              ->orWhereHas('user', fn($u) =>
                    $u->where('username', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%"))
              ->orWhereHas('post', fn($p) =>
                    $p->where('title', 'like', "%{$q}%"));
        });
    }

    $comments = $query->paginate($per)->appends($request->all());

    $rows = $comments->getCollection()->map(function ($c) {
        $username = $c->user?->username ?? $c->user?->email ?? 'Unknown';
        return [
            'id'         => $c->id,
            'user'       => $username,
            'comment'    => $c->content,
            'status'     => $c->status,
            'time_ago'   => optional($c->created_at)->diffForHumans(),
            'post_title' => $c->post?->title,
        ];
    });

    return response()->json([
        'data'  => $rows,
        'next'  => $comments->nextPageUrl(),
        'prev'  => $comments->previousPageUrl(),
        'total' => $comments->total(),
        'page'  => $comments->currentPage(),
        'last'  => $comments->lastPage(),
    ]);
}

    public function stats()
    {
        $total    = Comment::count();
        $pending  = Comment::where('status','Pending')->count();
        $flagged  = Comment::where('status','Flagged')->count();
        $approved = Comment::where('status','Approved')->count();

        $labels = [];
        $series = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->startOfDay();
            $labels[] = $day->format('D');
            $series[] = Comment::whereBetween('created_at', [$day, $day->copy()->endOfDay()])->count();
        }

        return response()->json([
            'cards'  => [
                'total'    => $total,
                'pending'  => $pending,
                'flagged'  => $flagged,
                'approved' => $approved,
            ],
            'trend' => ['labels' => $labels, 'data' => $series],
            'breakdown' => [
                'labels' => ['Approved','Pending','Flagged'],
                'data'   => [$approved, $pending, $flagged],
            ]
        ]);
    }

    public function live()
{
    $latest = Comment::with(['user:user_id,username,email'])
        ->latest()
        ->take(10)
        ->get()
        ->map(fn($c) => [
            'user' => $c->user?->username ?? $c->user?->email ?? 'Unknown',
            'text' => $c->content,
            'ago'  => optional($c->created_at)->diffForHumans(),
        ]);

    return response()->json(['items' => $latest]);
}

    public function approve(Request $request)
    {
        $id = $request->integer('id');
        $comment = Comment::findOrFail($id);
        $comment->status = 'Approved';
        $comment->save();
        return response()->json(['ok' => true]);
    }

    public function delete(Request $request)
    {
        $id = $request->integer('id');
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return response()->json(['ok' => true]);
    }

    public function bulk(Request $request)
    {
        $action = $request->string('action')->toString(); // approve|delete
        $ids    = $request->input('ids', []);

        if ($action === 'approve') {
            Comment::whereIn('id', $ids)->update(['status' => 'Approved']);
        } elseif ($action === 'delete') {
            Comment::whereIn('id', $ids)->delete();
        }

        return response()->json(['ok' => true]);
    }

    public function posts(Request $request)
{
    $status = $request->string('status')->toString(); // All|Pending|Approved|Flagged
    $q      = trim($request->string('q')->toString());
    $per    = (int) $request->integer('per', 10);

    $query = Post::with(['user:user_id,username'])
        ->latest('posts.created_at');

    if ($status && $status !== 'All') {
        $query->where('status', $status);
    }

    if ($q !== '') {
        $query->where(function ($w) use ($q) {
            $w->where('title', 'like', "%{$q}%")
              ->orWhereHas('user', fn($u) => $u->where('username', 'like', "%{$q}%"));
        });
    }

    $posts = $query->paginate($per)->appends($request->all());

    $rows = $posts->getCollection()->map(function ($p) {
        return [
            'id'       => $p->id ?? $p->post_id,
            'user'     => $p->user?->username ?? 'Unknown',
            'title'    => $p->title,
            'status'   => $p->status,
            'time_ago' => $p->created_at?->diffForHumans(),
        ];
    });

    return response()->json([
        'data'  => $rows,
        'next'  => $posts->nextPageUrl(),
        'prev'  => $posts->previousPageUrl(),
        'total' => $posts->total(),
        'page'  => $posts->currentPage(),
        'last'  => $posts->lastPage(),
    ]);
}

public function postApprove(Request $request)
{
    $id   = $request->integer('id');
    $post = Post::where('id', $id)->orWhere('post_id', $id)->firstOrFail();
    $post->status = 'Approved';
    $post->save();

    return response()->json(['ok' => true]);
}

public function postDelete(Request $request)
{
    $id   = $request->integer('id');
    $post = Post::where('id', $id)->orWhere('post_id', $id)->firstOrFail();
    $post->delete();

    return response()->json(['ok' => true]);
}

public function postBulk(Request $request)
{
    $action = $request->string('action')->toString(); // approve|delete
    $ids    = (array) $request->input('ids', []);

    $idCol = Schema::hasColumn('posts', 'id') ? 'id' : 'post_id';

    if ($action === 'approve') {
        Post::whereIn($idCol, $ids)->update(['status' => 'Approved']);
    } elseif ($action === 'delete') {
        Post::whereIn($idCol, $ids)->delete();
    }

    return response()->json(['ok' => true]);
}

}
