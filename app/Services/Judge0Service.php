<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Judge0Service
{
    private Client $client;
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('JUDGE0_API_KEY');
        $this->baseUrl = 'https://judge0-ce.p.rapidapi.com';
        $this->client = new Client([
            'headers' => [
                'X-RapidAPI-Key' => $this->apiKey,
                'X-RapidAPI-Host' => 'judge0-ce.p.rapidapi.com',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * Submit code to Judge0 for evaluation.
     *
     * @param string $sourceCode
     * @param int $languageId
     * @param string $input
     * @return array|null
     */
    public function submitCode(string $sourceCode, int $languageId, string $input): ?array
    {
        try {
            $response = $this->client->post("{$this->baseUrl}/submissions", [
                'json' => [
                    'source_code' => $sourceCode,
                    'language_id' => $languageId,
                    'stdin' => $input,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data;
        } catch (GuzzleException $e) {
            Log::error('Judge0 API error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get submission result from Judge0.
     *
     * @param string $token
     * @return array|null
     */
    public function getSubmissionResult(string $token): ?array
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/submissions/{$token}");
            $data = json_decode($response->getBody()->getContents(), true);
            return $data;
        } catch (GuzzleException $e) {
            Log::error('Judge0 API error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Evaluate code against test cases.
     *
     * @param string $sourceCode
     * @param int $languageId
     * @param array $testCases
     * @return string
     */
    public function evaluateCode(string $sourceCode, int $languageId, array $testCases): string
    {
        $allPassed = true;

        foreach ($testCases as $testCase) {
            $input = $testCase['input'];
            $expectedOutput = trim($testCase['output']);

            $submission = $this->submitCode($sourceCode, $languageId, $input);
            
            if (!$submission || !isset($submission['token'])) {
                return 'Judge Error: Failed to submit code';
            }

            // Wait for a moment to ensure the submission is processed
            sleep(2);

            $result = $this->getSubmissionResult($submission['token']);
            
            if (!$result) {
                return 'Judge Error: Failed to get submission result';
            }

            // Check for compilation errors
            if ($result['status']['id'] === 6) {
                return 'Compilation Error';
            }

            // Check for runtime errors
            if (in_array($result['status']['id'], [7, 8, 9, 10, 11, 12, 13, 14, 15])) {
                return $result['status']['description'];
            }

            // Compare output
            $actualOutput = trim($result['stdout'] ?? '');
            if ($actualOutput !== $expectedOutput) {
                $allPassed = false;
                break;
            }
        }

        return $allPassed ? 'Accepted' : 'Wrong Answer';
    }
} 