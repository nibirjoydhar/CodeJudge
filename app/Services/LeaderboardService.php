<?php

namespace App\Services;

use App\Models\User;
use App\Models\Submission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LeaderboardService
{
    /**
     * Cache key for the leaderboard data.
     *
     * @var string
     */
    protected const CACHE_KEY = 'leaderboard:top10';

    /**
     * Cache duration in seconds (5 minutes).
     *
     * @var int
     */
    protected const CACHE_DURATION = 300;

    /**
     * Get the top 10 users with the most accepted submissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTopUsers(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            return User::where('role', 'contestant')
                ->withCount(['submissions as solved_count' => function ($query) {
                    $query->where('status', 'Accepted');
                }])
                ->orderByDesc('solved_count')
                ->limit(10)
                ->get(['id', 'name', 'solved_count']);
        });
    }

    /**
     * Clear the leaderboard cache when a submission is updated.
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
} 