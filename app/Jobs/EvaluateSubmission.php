<?php

namespace App\Jobs;

use App\Models\Submission;
use App\Services\Judge0Service;
use App\Services\SubmissionCacheService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EvaluateSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $submission;

    /**
     * Create a new job instance.
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    /**
     * Execute the job.
     */
    public function handle(Judge0Service $judge0Service, SubmissionCacheService $cacheService): void
    {
        try {
            $problem = $this->submission->problem;
            $testCases = $problem->testCases()->get()->map(function($testCase) {
                return [
                    'input' => $testCase->input,
                    'expected_output' => $testCase->expected_output
                ];
            })->toArray();

            // Check cache first
            $cachedResult = $cacheService->getCachedResult(
                $this->submission->code,
                $this->submission->language_id,
                $this->submission->problem_id
            );

            if ($cachedResult) {
                // Update submission with cached result
                $this->submission->update([
                    'status' => $cachedResult['status'],
                    'points' => $cachedResult['points']
                ]);

                Log::info('Used cached submission result', [
                    'submission_id' => $this->submission->id,
                    'status' => $cachedResult['status'],
                    'points' => $cachedResult['points']
                ]);

                return;
            }

            // Use Judge0 service to evaluate the code
            $status = $judge0Service->evaluateCode(
                $this->submission->code, 
                $this->submission->language_id, 
                $testCases
            );
            
            // Calculate points based on status
            $points = $status === 'Accepted' ? 100 : 0;

            // Cache the result
            $cacheService->cacheResult(
                $this->submission->code,
                $this->submission->language_id,
                $this->submission->problem_id,
                $status,
                $points
            );

            // Update submission with results
            $this->submission->update([
                'status' => $status,
                'points' => $points
            ]);

            Log::info('Submission evaluated successfully', [
                'submission_id' => $this->submission->id,
                'status' => $status,
                'points' => $points
            ]);
        } catch (\Exception $e) {
            Log::error('Error evaluating submission', [
                'submission_id' => $this->submission->id,
                'error' => $e->getMessage()
            ]);

            // Update submission with error status
            $this->submission->update([
                'status' => 'Evaluation Error: ' . $e->getMessage(),
                'points' => 0
            ]);
        }
    }
} 