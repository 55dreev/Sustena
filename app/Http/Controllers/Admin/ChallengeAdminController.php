<?php
// app/Http/Controllers/Admin/ChallengeAdminController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeAdminController extends Controller
{
  public function index() {
    $items = Challenge::orderBy('difficulty')->orderBy('title')->paginate(20);
    return view('admin.challenges.index', compact('items'));
  }

  public function create() {
    return view('admin.challenges.form', ['item' => new Challenge()]);
  }

  public function store(Request $r) {
    $data = $r->validate([
      'title'=>'required|string|max:255|unique:challenges,title',
      'subtitle'=>'nullable|string|max:255',
      'description'=>'nullable|string',
      'difficulty'=>'required|integer|min:1|max:3',
      'points_xp'=>'required|integer|min:0|max:10000',
      'icon'=>'nullable|string|max:32',
      'is_active'=>'boolean',
    ]);
    $data['icon'] = $data['icon'] ?? '🌱';
    Challenge::create($data);
    return redirect()->route('challenges.index')->with('ok','Created');
  }

  public function edit(Challenge $challenge) {
    return view('admin.challenges.form', ['item'=>$challenge]);
  }

  public function update(Request $r, Challenge $challenge) {
    $data = $r->validate([
      'title'=>'required|string|max:255|unique:challenges,title,'.$challenge->id,
      'subtitle'=>'nullable|string|max:255',
      'description'=>'nullable|string',
      'difficulty'=>'required|integer|min:1|max:3',
      'points_xp'=>'required|integer|min:0|max:10000',
      'icon'=>'nullable|string|max:32',
      'is_active'=>'boolean',
    ]);
    $data['icon'] = $data['icon'] ?? '🌱';
    $challenge->update($data);
    return redirect()->route('challenges.index')->with('ok','Updated');
  }

  public function destroy(Challenge $challenge) {
    $challenge->delete();
    return back()->with('ok','Deleted');
  }
}
