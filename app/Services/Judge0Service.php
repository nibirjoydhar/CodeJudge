<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Judge0Service
{
    private Client $client;
    private ?string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('JUDGE0_API_KEY');
        $this->baseUrl = 'https://judge0-ce.p.rapidapi.com';
        
        if (empty($this->apiKey)) {
            Log::error('Judge0 API key is missing. Please set JUDGE0_API_KEY in your .env file.');
        }
        
        $this->client = new Client([
            'headers' => [
                'X-RapidAPI-Key' => $this->apiKey ?? '',
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
        if (empty($this->apiKey)) {
            Log::error('Cannot submit code: Judge0 API key is missing');
            return null;
        }
        
        try {
            // Base64 encode the source code and input
            $encodedSourceCode = base64_encode($sourceCode);
            $encodedInput = base64_encode($input);
            
            $response = $this->client->post("{$this->baseUrl}/submissions?base64_encoded=true&wait=false&fields=*", [
                'json' => [
                    'source_code' => $encodedSourceCode,
                    'language_id' => $languageId,
                    'stdin' => $encodedInput,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data;
        } catch (GuzzleException $e) {
            $message = $e->getMessage();
            Log::error('Judge0 API error: ' . $message);
            
            // Check if this is a subscription issue
            if (strpos($message, '403 Forbidden') !== false) {
                Log::error('Judge0 API subscription issue. Please subscribe to the Judge0 API on RapidAPI.');
            }
            
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
        if (empty($this->apiKey)) {
            Log::error('Cannot get submission result: Judge0 API key is missing');
            return null;
        }
        
        try {
            $response = $this->client->get("{$this->baseUrl}/submissions/{$token}?base64_encoded=true&fields=*");
            $data = json_decode($response->getBody()->getContents(), true);
            
            // If output is base64 encoded, decode it
            if (isset($data['stdout']) && !empty($data['stdout'])) {
                $data['stdout'] = base64_decode($data['stdout']);
            }
            
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
        if (empty($this->apiKey)) {
            return 'Configuration Error: Judge0 API key is missing. Please contact the administrator.';
        }
        
        $allPassed = true;

        foreach ($testCases as $testCase) {
            $input = $testCase['input'];
            $expectedOutput = trim($testCase['output']);

            $submission = $this->submitCode($sourceCode, $languageId, $input);
            
            if (!$submission || !isset($submission['token'])) {
                return 'Judge Error: Failed to submit code. The Judge0 API might be unavailable or the API key might be invalid.';
            }

            // Wait for a moment to ensure the submission is processed
            sleep(3);

            $result = $this->getSubmissionResult($submission['token']);
            
            if (!$result) {
                return 'Judge Error: Failed to get submission result';
            }

            // Check for compilation errors
            if (isset($result['status']) && $result['status']['id'] === 6) {
                return 'Compilation Error: ' . (isset($result['compile_output']) ? base64_decode($result['compile_output']) : '');
            }

            // Check for runtime errors
            if (isset($result['status']) && in_array($result['status']['id'], [7, 8, 9, 10, 11, 12, 13, 14, 15])) {
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