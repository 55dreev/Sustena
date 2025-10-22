<?php

// app/Http/Controllers/BadgesController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BadgesController extends Controller
{
    public function index()
    {
        $u = Auth::user();
$userId = $u?->user_id ?? $u?->id; // OK for both shapes

$all = DB::table('badges')
  ->select('id','slug','name','category','points_reward')
  ->orderBy('category')->orderBy('id')
  ->get();

$earned = DB::table('user_badges as ub')
  ->join('badges as b','b.id','=','ub.badge_id')
  ->where('ub.user_id', $userId)
  ->orderByDesc('ub.awarded_at')
  ->get(['b.id','b.slug','b.name','b.category','b.points_reward','ub.awarded_at']);

$earnedSlugs = $earned->pluck('slug')->all();

$pointsTotal = (int)($u->points_total ?? 0);
$level       = (int)($u->level ?? 1);

return view('badges', compact('all','earned','earnedSlugs','pointsTotal','level'));
    }
}
