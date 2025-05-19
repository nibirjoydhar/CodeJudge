<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of comments for a specific problem.
     *
     * @param  Problem  $problem
     * @return View
     */
    public function index(Problem $problem): View
    {
        $comments = $problem->comments()
            ->with('user')
            ->latest()
            ->get();
        
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
        
        return redirect()->route('comments.index', $problem)
            ->with('success', 'Comment posted successfully.');
    }
}
