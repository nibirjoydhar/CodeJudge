<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for a specific problem.
     *
     * @param  Problem  $problem
     * @return View
     */
    public function index(Problem $problem): View
    {
        // Add logging for debugging
        Log::info('Accessing comments for problem: ' . $problem->id);
        
        $comments = $problem->comments()
            ->with('user')
            ->latest()
            ->get();
        
        // Add logging for number of comments found
        Log::info('Found ' . $comments->count() . ' comments for problem ' . $problem->id);
        
        return view('comments.index', [
            'problem' => $problem,
            'comments' => $comments
        ]);
    }
    
    /**
     * Store a newly created comment in storage.
     *
     * @param  Request  $request
     * @param  Problem  $problem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Problem $problem)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        $comment = new Comment([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'problem_id' => $problem->id,
        ]);
        
        $comment->save();
        
        Log::info('New comment created for problem ' . $problem->id . ' by user ' . auth()->id());
        
        return redirect()->route('comments.index', $problem)
            ->with('success', 'Comment posted successfully.');
    }
}
