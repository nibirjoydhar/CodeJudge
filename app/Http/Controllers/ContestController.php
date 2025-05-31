<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ContestController extends Controller
{
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

        $contest = Contest::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'password' => Hash::make($validated['password']),
            'created_by' => auth()->id()
        ]);

        $contest->problems()->attach($validated['problems']);

        return redirect()->route('contests.show', $contest)
            ->with('success', 'Contest created successfully!');
    }

    public function show(Contest $contest)
    {
        $isParticipant = $contest->participants()->where('user_id', auth()->id())->exists();
        $canViewSubmissions = $contest->hasEnded() || auth()->user()->id === $contest->created_by;
        
        $submissions = collect();
        $rankings = collect();
        
        if ($isParticipant || $canViewSubmissions) {
            $submissions = $contest->submissions()
                ->with(['user', 'problem'])
                ->orderBy('created_at', 'desc')
                ->get();

            $rankings = $contest->participants()
                ->withCount(['submissions as accepted_count' => function($query) {
                    $query->where('verdict', 'Accepted');
                }])
                ->orderByDesc('accepted_count')
                ->get();
        }

        return view('contests.show', compact('contest', 'isParticipant', 'canViewSubmissions', 'submissions', 'rankings'));
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

        if ($contest->hasEnded()) {
            return back()->with('error', 'This contest has ended.');
        }

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
} 