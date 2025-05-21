<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProblemListController extends Controller
{
    /**
     * Display a listing of the problems.
     *
     * @return View
     */
    public function index(): View
    {
        $problems = Problem::orderBy('id')->get();
        
        return view('problems.list', [
            'problems' => $problems
        ]);
    }
    
    /**
     * Display the specified problem.
     *
     * @param Problem $problem
     * @return View
     */
    public function show(Problem $problem): View
    {
        $submissions = auth()->check() 
            ? auth()->user()->submissions()->where('problem_id', $problem->id)
                ->with('problem')
                ->latest()
                ->take(5)
                ->get()
            : collect();
            
        // Get the most recent submission for this problem by the current user
        $latestSubmission = auth()->check()
            ? auth()->user()->submissions()
                ->where('problem_id', $problem->id)
                ->latest()
                ->first()
            : null;
            
        return view('problems.show', compact('problem', 'submissions', 'latestSubmission'));
    }
} 