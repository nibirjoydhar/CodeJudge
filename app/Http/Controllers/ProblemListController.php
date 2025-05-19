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
} 