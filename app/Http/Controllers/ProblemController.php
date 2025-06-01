<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProblemController extends Controller
{
    /**
     * Display a listing of the problems.
     */
    public function index()
    {
        $problems = Problem::withCount('testCases')->get();
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
            'test_cases' => ['required', 'array', 'min:1'],
            'test_cases.*.input' => 'required|string',
            'test_cases.*.expected_output' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $problem = Problem::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['test_cases'] as $testCase) {
                $problem->testCases()->create([
                    'input' => $testCase['input'],
                    'expected_output' => $testCase['expected_output'],
                    'is_sample' => $testCase['is_sample'] ?? false,
                    'points' => $testCase['points'] ?? 0,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.problems.index')
                ->with('success', 'Problem created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create problem. ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified problem.
     */
    public function edit(Problem $problem)
    {
        $problem->load('testCases');
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
            'test_cases' => ['required', 'array', 'min:1'],
            'test_cases.*.input' => 'required|string',
            'test_cases.*.expected_output' => 'required|string',
            'test_cases.*.is_sample' => 'nullable|boolean',
            'test_cases.*.points' => 'nullable|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $problem->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            // Delete existing test cases
            $problem->testCases()->delete();

            // Create new test cases
            foreach ($validated['test_cases'] as $testCase) {
                $problem->testCases()->create([
                    'input' => $testCase['input'],
                    'expected_output' => $testCase['expected_output'],
                    'is_sample' => isset($testCase['is_sample']) && $testCase['is_sample'] == '1',
                    'points' => $testCase['points'] ?? 0
                ]);
            }

            DB::commit();
            return redirect()->route('admin.problems.index')
                ->with('success', 'Problem updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update problem. ' . $e->getMessage()]);
        }
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