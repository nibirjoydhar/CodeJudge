<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Jobs\EvaluateSubmission;
use App\Services\SubmissionCacheService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ContestController extends Controller
{
    protected SubmissionCacheService $cacheService;

    public function __construct(SubmissionCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function index()
    {
        $contests = Contest::with('creator')
            ->orderBy('start_time', 'desc')
            ->paginate(10);
        
        return view('contests.index', compact('contests'));
    }

    public function create()
    {
        $problems = Problem::all();
        return view('contests.create', compact('problems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'password' => 'required|string|min:6',
            'problems' => 'required|array|min:1',
            'problems.*' => 'exists:problems,id'
        ]);

        // Convert times to Bangladesh timezone
        $startTime = Carbon::parse($validated['start_time'])->setTimezone('Asia/Dhaka');
        $endTime = Carbon::parse($validated['end_time'])->setTimezone('Asia/Dhaka');

        $contest = Contest::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'password' => Hash::make($validated['password']),
            'created_by' => auth()->id()
        ]);

        $contest->problems()->attach($validated['problems']);

        return redirect()->route('contests.show', $contest)
            ->with('success', 'Contest created successfully!');
    }

    public function show(Contest $contest)
    {
        $user = auth()->user();
        
        // Check if user can access the contest
        if (!$contest->canAccess($user)) {
            return redirect()->route('contests.index')
                ->with('error', 'You do not have access to this contest or it has not started yet.');
        }

        $status = $contest->getStatus();
        $isParticipant = $contest->participants()->where('user_id', $user->id)->exists();
        $rankings = $contest->getRankings();
        
        return view('contests.show', compact('contest', 'status', 'isParticipant', 'rankings'));
    }

    public function join(Request $request, Contest $contest)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        if (!Hash::check($request->password, $contest->password)) {
            throw ValidationException::withMessages([
                'password' => ['Incorrect password']
            ]);
        }

        // Check if already a participant
        if ($contest->participants()->where('user_id', auth()->id())->exists()) {
            return back()->with('info', 'You are already a participant in this contest.');
        }

        // Check if contest has ended
        if ($contest->hasEnded()) {
            return back()->with('error', 'This contest has ended.');
        }

        // Add user as participant
        $contest->participants()->attach(auth()->id(), [
            'joined_at' => now()
        ]);

        return redirect()->route('contests.show', $contest)
            ->with('success', 'Successfully joined the contest!');
    }

    public function submit(Request $request, Contest $contest, Problem $problem)
    {
        if (!$contest->isActive()) {
            return back()->with('error', 'Contest is not active.');
        }

        if (!$contest->participants()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You are not a participant of this contest.');
        }

        $validated = $request->validate([
            'language' => 'required|string',
            'code' => 'required|string',
        ]);

        $submission = new Submission([
            'language' => $validated['language'],
            'code' => $validated['code'],
            'user_id' => auth()->id(),
            'problem_id' => $problem->id,
            'contest_id' => $contest->id
        ]);

        $submission->save();

        // Here you would typically trigger your judging system
        // For now, we'll just redirect back

        return redirect()->route('contests.show', $contest)
            ->with('success', 'Solution submitted successfully!');
    }

    public function showProblem(Contest $contest, Problem $problem)
    {
        $user = auth()->user();
        
        // Check if user can access the contest
        if (!$contest->canAccess($user)) {
            return redirect()->route('contests.index')
                ->with('error', 'You do not have access to this contest or it has not started yet.');
        }

        // Check if problem belongs to contest
        if (!$contest->problems()->where('problems.id', $problem->id)->exists()) {
            return redirect()->route('contests.show', $contest)
                ->with('error', 'This problem is not part of the contest.');
        }

        return view('contests.problems.show', compact('contest', 'problem'));
    }

    public function showSubmitForm(Contest $contest, Problem $problem)
    {
        $user = auth()->user();
        
        // Check if user can access the contest and it's running
        if (!$contest->canAccess($user) || $contest->getStatus() !== 'Running') {
            return redirect()->route('contests.show', $contest)
                ->with('error', 'You cannot submit solutions at this time.');
        }

        // Check if problem belongs to contest
        if (!$contest->problems()->where('problems.id', $problem->id)->exists()) {
            return redirect()->route('contests.show', $contest)
                ->with('error', 'This problem is not part of the contest.');
        }

        return view('contests.problems.submit', compact('contest', 'problem'));
    }

    public function submitSolution(Request $request, Contest $contest, Problem $problem)
    {
        $user = auth()->user();
        
        // Check if user can access the contest and it's running
        if (!$contest->canAccess($user) || $contest->getStatus() !== 'Running') {
            return redirect()->route('contests.show', $contest)
                ->with('error', 'You cannot submit solutions at this time.');
        }

        // Validate submission
        $validated = $request->validate([
            'language_id' => 'required|integer|in:54,71,62',
            'code' => 'required|string'
        ]);

        // Check if we have a cached result for this submission
        $cachedResult = $this->cacheService->getCachedResult(
            $validated['code'],
            $validated['language_id'],
            $problem->id
        );

        // Create submission
        $submission = new Submission([
            'user_id' => $user->id,
            'problem_id' => $problem->id,
            'contest_id' => $contest->id,
            'language_id' => $validated['language_id'],
            'code' => $validated['code'],
            'status' => $cachedResult ? $cachedResult['status'] : 'Pending',
            'points' => $cachedResult ? $cachedResult['points'] : 0
        ]);

        $submission->save();

        if (!$cachedResult) {
            // Dispatch job to evaluate submission
            EvaluateSubmission::dispatch($submission);
        }

        return redirect()->route('contests.problems.submit', [$contest, $problem])
            ->with('success', 'Solution submitted successfully. ' . 
                ($cachedResult ? 'Result: ' . $cachedResult['status'] : 'Please wait for the evaluation result.'));
    }

    public function problems(Contest $contest)
    {
        try {
            $user = auth()->user();
            
            if (!$contest->canAccess($user)) {
                return redirect()->route('contests.index')
                    ->with('error', 'You do not have access to this contest.');
            }

            $problems = $contest->problems()
                ->orderBy('contest_problems.created_at')
                ->get();

            Log::info('Contest problems accessed', [
                'contest_id' => $contest->id,
                'user_id' => $user->id,
                'problem_count' => $problems->count()
            ]);

            return view('contests.problems.index', compact('contest', 'problems'));
        } catch (\Exception $e) {
            Log::error('Error accessing contest problems', [
                'contest_id' => $contest->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('contests.show', $contest)
                ->with('error', 'There was an error loading the problems. Please try again.');
        }
    }

    public function submissions(Contest $contest)
    {
        $user = auth()->user();
        
        if (!$contest->canAccess($user)) {
            return redirect()->route('contests.index')
                ->with('error', 'You do not have access to this contest.');
        }

        $submissions = $contest->submissions()
            ->with(['user', 'problem'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('contests.submissions', compact('contest', 'submissions'));
    }

    public function standings(Contest $contest)
    {
        $user = auth()->user();
        
        if (!$contest->canAccess($user)) {
            return redirect()->route('contests.index')
                ->with('error', 'You do not have access to this contest.');
        }

        $rankings = $contest->getRankings();
        return view('contests.standings', compact('contest', 'rankings'));
    }

    public function edit(Contest $contest)
    {
        $problems = Problem::all();
        $selectedProblems = $contest->problems->pluck('id')->toArray();
        
        return view('contests.edit', compact('contest', 'problems', 'selectedProblems'));
    }

    public function update(Request $request, Contest $contest)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'password' => 'nullable|string|min:6',
            'problems' => 'required|array|min:1',
            'problems.*' => 'exists:problems,id'
        ]);

        // Convert times to Bangladesh timezone
        $startTime = Carbon::parse($validated['start_time'])->setTimezone('Asia/Dhaka');
        $endTime = Carbon::parse($validated['end_time'])->setTimezone('Asia/Dhaka');

        $contest->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        if (!empty($validated['password'])) {
            $contest->update(['password' => Hash::make($validated['password'])]);
        }

        $contest->problems()->sync($validated['problems']);

        return redirect()->route('contests.show', $contest)
            ->with('success', 'Contest updated successfully!');
    }
} 