<?php


// app/Http/Controllers/ChallengePageController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ChallengePageController extends Controller {
  public function index() {
    return view('challenge.index'); // your Blade file below
  }
}
