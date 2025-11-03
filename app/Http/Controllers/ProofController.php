<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProofController extends Controller
{
    public function show($filename)
    {
        // Sanitize filename
        $filename = basename($filename);

        // Check if file exists in storage/app/public/proofs
        if (!Storage::disk('public')->exists('proofs/' . $filename)) {
            abort(404); // file not found
        }

        // Return the file response
        return response()->file(storage_path('app/public/proofs/' . $filename));
    }
}