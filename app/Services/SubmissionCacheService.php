<?php

namespace App\Services;

use App\Models\Submission;
use Illuminate\Support\Facades\Cache;

class SubmissionCacheService
{
    /**
     * Get cached submission result for identical code and problem
     */
    public function getCachedResult(string $code, int $languageId, int $problemId): ?array
    {
        $cacheKey = $this->generateCacheKey($code, $languageId, $problemId);
        return Cache::get($cacheKey);
    }

    /**
     * Cache submission result for future reuse
     */
    public function cacheResult(string $code, int $languageId, int $problemId, string $status, int $points): void
    {
        $cacheKey = $this->generateCacheKey($code, $languageId, $problemId);
        Cache::put($cacheKey, [
            'status' => $status,
            'points' => $points
        ], now()->addHours(24)); // Cache for 24 hours
    }

    /**
     * Generate a unique cache key for the submission
     */
    protected function generateCacheKey(string $code, int $languageId, int $problemId): string
    {
        // Create a hash of the code to use as part of the cache key
        $codeHash = md5($code);
        return "submission_result:{$problemId}:{$languageId}:{$codeHash}";
    }
} 