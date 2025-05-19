<?php

namespace App\Http\Controllers;

use App\Services\LeaderboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * The leaderboard service instance.
     *
     * @var LeaderboardService
     */
    protected $leaderboardService;

    /**
     * Create a new controller instance.
     *
     * @param LeaderboardService $leaderboardService
     */
    public function __construct(LeaderboardService $leaderboardService)
    {
        $this->leaderboardService = $leaderboardService;
    }

    /**
     * Display the leaderboard.
     *
     * @return View
     */
    public function index(): View
    {
        $topUsers = $this->leaderboardService->getTopUsers();
        
        return view('leaderboard.index', [
            'topUsers' => $topUsers,
        ]);
    }
} 