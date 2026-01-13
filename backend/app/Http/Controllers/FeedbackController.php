<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index()
    {
        return DB::table('feedback')->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:255',
            'temperature' => 'required|integer',
            'comment' => 'required|string|max:500',
        ]);

        DB::table('feedback')->insert([
            'city' => $validated['city'],
            'temperature' => $validated['temperature'],
            'comment' => $validated['comment'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Відгук збережено! ✅']);
    }
}
