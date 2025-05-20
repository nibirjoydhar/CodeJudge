<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProblemController extends Controller
{
    /**
     * Display a listing of the problems.
     */
    public function index()
    {
        $problems = Problem::all();
        return view('problems.index', compact('problems'));
    }

    /**
     * Show the form for creating a new problem.
     */
    public function create()
    {
        return view('problems.create');
    }

    /**
     * Store a newly created problem in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'test_cases' => ['required', function ($attribute, $value, $fail) {
                if (!is_array(json_decode($value, true))) {
                    $fail('The '.$attribute.' must be a valid JSON array.');
                }
            }],
        ]);

        $validated['test_cases'] = json_decode($validated['test_cases'], true);
        Problem::create($validated);

        return redirect()->route('admin.problems.index')
            ->with('success', 'Problem created successfully');
    }

    /**
     * Show the form for editing the specified problem.
     */
    public function edit(Problem $problem)
    {
        return view('problems.edit', compact('problem'));
    }

    /**
     * Update the specified problem in storage.
     */
    public function update(Request $request, Problem $problem)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'test_cases' => ['required', function ($attribute, $value, $fail) {
                if (!is_array(json_decode($value, true))) {
                    $fail('The '.$attribute.' must be a valid JSON array.');
                }
            }],
        ]);

        $validated['test_cases'] = json_decode($validated['test_cases'], true);
        $problem->update($validated);

        return redirect()->route('admin.problems.index')
            ->with('success', 'Problem updated successfully');
    }

    /**
     * Remove the specified problem from storage.
     */
    public function destroy(Problem $problem)
    {
        $problem->delete();

        return redirect()->route('admin.problems.index')
            ->with('success', 'Problem deleted successfully');
    }
} 