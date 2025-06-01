<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\Submission;
use App\Services\Judge0Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SubmissionController extends Controller
{
    protected Judge0Service $judge0Service;

    public function __construct(Judge0Service $judge0Service)
    {
        $this->judge0Service = $judge0Service;
    }

    /**
     * Display a listing of the submissions.
     */
    public function index(Request $request): View
    {
        $submissions = Submission::where('user_id', $request->user()->id)
            ->with('problem')
            ->latest()
            ->paginate(10);

        return view('submissions.index', compact('submissions'));
    }

    /**
     * Show the form for creating a new submission.
     */
    public function create(Request $request): View
    {
        $problems = Problem::all();
        $languages = [
            ['id' => 54, 'name' => 'C++'],
            ['id' => 71, 'name' => 'Python'],
            ['id' => 62, 'name' => 'Java'],
        ];
        
        $selectedProblemId = $request->query('problem_id');

        return view('submissions.create', compact('problems', 'languages', 'selectedProblemId'));
    }

    /**
     * Store a newly created submission in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'problem_id' => 'required|exists:problems,id',
            'code' => 'required|string',
            'language_id' => 'required|in:54,71,62',
        ]);

        $problem = Problem::findOrFail($validated['problem_id']);
        $testCases = $problem->testCases()->get()->map(function($testCase) {
            return [
                'input' => $testCase->input,
                'expected_output' => $testCase->expected_output
            ];
        })->toArray();

        // Check if we're in a local/development environment or if the Judge0 integration is unavailable
        if (app()->environment('local') && (
            empty(env('JUDGE0_API_KEY')) || 
            str_contains(env('APP_DEBUG', false), 'true')
        )) {
            // Simulate code evaluation in development environment
            $status = 'Simulated Accepted';
            $points = 100; // Default points for simulated acceptance
        } else {
            // Use real Judge0 service for production
            $status = $this->judge0Service->evaluateCode(
                $validated['code'], 
                $validated['language_id'], 
                $testCases
            );
            
            // Calculate points based on status
            $points = $status === 'Accepted' ? 100 : 0;
        }

        // Create submission record
        $submission = Submission::create([
            'user_id' => $request->user()->id,
            'problem_id' => $validated['problem_id'],
            'code' => $validated['code'],
            'language_id' => $validated['language_id'],
            'status' => $status,
            'points' => $points
        ]);

        return redirect()->route('submissions.index')
            ->with('success', 'Submission created successfully with status: ' . $status);
    }

    /**
     * Display the specified submission.
     */
    public function show(Submission $submission): View
    {
        // Ensure users can only view their own submissions
        if ($submission->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('submissions.show', compact('submission'));
    }
} 