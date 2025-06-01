<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Judge0Service
{
    protected $apiKey;
    protected $baseUrl;
    protected $simulate;

    // Judge0 status codes
    const STATUS_IN_QUEUE = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_ACCEPTED = 3;
    const STATUS_WRONG_ANSWER = 4;
    const STATUS_TIME_LIMIT = 5;
    const STATUS_COMPILATION_ERROR = 6;
    const STATUS_RUNTIME_ERROR = 7;
    const STATUS_INTERNAL_ERROR = 8;
    const STATUS_EXEC_FORMAT_ERROR = 9;
    const STATUS_SYSTEM_ERROR = 10;
    const STATUS_MEMORY_LIMIT = 11;

    public function __construct()
    {
        $this->apiKey = Config::get('judge0.api_key');
        $this->baseUrl = Config::get('judge0.api_url');
        $this->simulate = Config::get('judge0.simulate', false);
    }

    public function evaluateCode(string $code, int $languageId, array $testCases): string
    {
        // Log the incoming parameters
        Log::info('Starting code evaluation', [
            'language_id' => $languageId,
            'test_case_count' => count($testCases),
            'code_length' => strlen($code)
        ]);

        // Only simulate if explicitly configured to do so
        if ($this->simulate) {
            Log::info('Running in simulation mode');
            return 'Simulated Accepted';
        }

        // Check if API key is configured
        if (empty($this->apiKey)) {
            Log::error('Judge0 API key not configured');
            return 'Configuration Error: API key not set';
        }

        // Initialize variables for tracking test case results
        $allPassed = true;
        $firstFailure = null;

        // Process each test case
        foreach ($testCases as $index => $testCase) {
            $testCaseNumber = $index + 1;
            Log::info("Processing test case {$testCaseNumber}", [
                'input_length' => strlen($testCase['input']),
                'expected_output_length' => strlen($testCase['expected_output'])
            ]);

            $submission = $this->createSubmission($code, $languageId, $testCase['input']);
            
            if (!$submission) {
                Log::error("Failed to create submission for test case {$testCaseNumber}");
                return 'Submission Error: Failed to create submission';
            }

            $result = $this->getSubmissionResult($submission['token']);
            
            if (!$result) {
                Log::error("Failed to get result for test case {$testCaseNumber}");
                return 'Evaluation Error: Failed to get result';
            }

            // Log the result for debugging
            Log::info("Test case {$testCaseNumber} result", [
                'status' => $result['status'],
                'stdout' => $result['stdout'] ?? null,
                'stderr' => $result['stderr'] ?? null,
                'compile_output' => $result['compile_output'] ?? null
            ]);

            // Check status
            switch ($result['status']['id']) {
                case self::STATUS_ACCEPTED:
                    // Check output
                    $expectedOutput = trim($testCase['expected_output']);
                    $actualOutput = trim($result['stdout'] ?? '');

                    if ($expectedOutput !== $actualOutput) {
                        $allPassed = false;
                        if (!$firstFailure) {
                            $firstFailure = "Wrong Answer\nExpected: $expectedOutput\nGot: $actualOutput";
                        }
                    }
                    break;

                case self::STATUS_COMPILATION_ERROR:
                    return 'Compilation Error: ' . ($result['compile_output'] ?? 'Unknown error');

                case self::STATUS_TIME_LIMIT:
                    return 'Time Limit Exceeded';

                case self::STATUS_MEMORY_LIMIT:
                    return 'Memory Limit Exceeded';

                case self::STATUS_RUNTIME_ERROR:
                case self::STATUS_INTERNAL_ERROR:
                case self::STATUS_EXEC_FORMAT_ERROR:
                case self::STATUS_SYSTEM_ERROR:
                    return 'Runtime Error: ' . ($result['stderr'] ?? 'Unknown error');

                default:
                    return 'Unknown Error: Status ' . $result['status']['id'];
            }
        }

        return $allPassed ? 'Accepted' : $firstFailure;
    }

    protected function createSubmission(string $code, int $languageId, string $input)
    {
        try {
            // Log API request details (excluding sensitive data)
            Log::info('Making Judge0 API request', [
                'base_url' => $this->baseUrl,
                'language_id' => $languageId,
                'code_length' => strlen($code),
                'input_length' => strlen($input),
                'has_api_key' => !empty($this->apiKey)
            ]);

            // Prepare headers based on whether we're using RapidAPI or local instance
            $headers = ['Content-Type' => 'application/json'];
            if ($this->apiKey) {
                $headers['X-RapidAPI-Host'] = 'judge0-ce.p.rapidapi.com';
                $headers['X-RapidAPI-Key'] = $this->apiKey;
            }

            // Prepare request payload
            $payload = [
                'source_code' => base64_encode($code),
                'language_id' => $languageId,
                'stdin' => base64_encode($input),
                'expected_output' => null,
                'cpu_time_limit' => 2,
                'cpu_extra_time' => 0.5,
                'wall_time_limit' => 5,
                'memory_limit' => 128000,
                'stack_limit' => 64000,
                'max_processes_and_or_threads' => 60,
                'enable_per_process_and_thread_time_limit' => false,
                'enable_per_process_and_thread_memory_limit' => false,
                'max_file_size' => 1024
            ];

            Log::info('Judge0 request payload', ['payload' => $payload]);

            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->post($this->baseUrl . '/submissions?base64_encoded=true&wait=false', $payload);

            if (!$response->successful()) {
                Log::error('Judge0 API Error Response', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'url' => $this->baseUrl . '/submissions',
                    'headers' => $response->headers()
                ]);
                return null;
            }

            $jsonResponse = $response->json();
            Log::info('Judge0 API Success Response', [
                'token' => $jsonResponse['token'] ?? 'no_token',
                'status' => $response->status(),
                'response' => $jsonResponse
            ]);

            return $jsonResponse;
        } catch (\Exception $e) {
            Log::error('Judge0 API Exception', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    protected function getSubmissionResult(string $token)
    {
        try {
            $maxAttempts = 10;
            $attempt = 0;
            
            // Prepare headers based on whether we're using RapidAPI or local instance
            $headers = ['Content-Type' => 'application/json'];
            if ($this->apiKey) {
                $headers['X-RapidAPI-Host'] = 'judge0-ce.p.rapidapi.com';
                $headers['X-RapidAPI-Key'] = $this->apiKey;
            }

            do {
                $response = Http::withHeaders($headers)
                    ->timeout(30)
                    ->get($this->baseUrl . '/submissions/' . $token . '?base64_encoded=true');

                if ($response->successful()) {
                    $result = $response->json();

                    // Decode base64 outputs if present
                    if (!empty($result['stdout'])) {
                        $result['stdout'] = base64_decode($result['stdout']);
                    }
                    if (!empty($result['stderr'])) {
                        $result['stderr'] = base64_decode($result['stderr']);
                    }
                    if (!empty($result['compile_output'])) {
                        $result['compile_output'] = base64_decode($result['compile_output']);
                    }

                    if ($result['status']['id'] !== self::STATUS_IN_QUEUE && 
                        $result['status']['id'] !== self::STATUS_PROCESSING) {
                        return $result;
                    }
                }

                sleep(1); // Wait 1 second before next attempt
                $attempt++;
            } while ($attempt < $maxAttempts);

            return null;
        } catch (\Exception $e) {
            Log::error('Judge0 API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
} 