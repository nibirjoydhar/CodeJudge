<?php

namespace Database\Seeders;

use App\Models\Problem;
use App\Models\TestCase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Temporarily disable foreign key checks
        Problem::truncate(); // Clear existing problems
        TestCase::truncate(); // Clear existing test cases (if any from previous runs)
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Re-enable foreign key checks

        $problems = [
            // Problem 1: Sum of Two Numbers (Easy)
            [
                'title' => 'Sum of Two Numbers',
                'description' => 'Given two integers, return their sum.',
                'input_format' => 'Two space-separated integers, A and B.',
                'output_format' => 'A single integer, the sum of A and B.',
                'constraints' => '-10^9 <= A, B <= 10^9',
                'sample_input' => '7 12',
                'sample_output' => '19',
                'explanation' => 'The sum of 7 and 12 is 19.',
                'difficulty' => 'easy',
                'created_by' => 1, // Assuming user with ID 1 exists
            ],
            // Problem 2: Palindrome Checker (Medium)
            [
                'title' => 'Palindrome Checker',
                'description' => 'Given a string, determine if it is a palindrome. A palindrome is a word, phrase, number, or other sequence of characters which reads the same backward as forward. Ignore case sensitivity and non-alphanumeric characters.',
                'input_format' => 'A single line containing a string S.',
                'output_format' => 'Print "YES" if the string is a palindrome, otherwise print "NO".',
                'constraints' => '1 <= |S| <= 1000' . "\n" . 'S will contain ASCII characters.',
                'sample_input' => 'Racecar',
                'sample_output' => 'YES',
                'explanation' => 'Ignoring case and non-alphanumeric characters, "Racecar" becomes "racecar" which reads the same forwards and backwards.',
                'difficulty' => 'medium',
                'created_by' => 1,
            ],
            // Problem 3: Smallest Missing Positive Integer (Hard)
            [
                'title' => 'Smallest Missing Positive Integer',
                'description' => 'Given an unsorted array of integers, find the smallest missing positive integer.',
                'input_format' => 'The first line contains an integer N, the number of elements in the array.' . "\n" . 'The second line contains N space-separated integers representing the array elements.',
                'output_format' => 'A single integer, the smallest missing positive integer.',
                'constraints' => '1 <= N <= 10^5' . "\n" . '-10^9 <= Array elements <= 10^9',
                'sample_input' => "6\n1 5 2 6 0 3",
                'sample_output' => '4',
                'explanation' => 'The positive integers are 1, 2, 3, 5, 6. The smallest positive integer not present is 4.',
                'difficulty' => 'hard',
                'created_by' => 1,
            ],
            // Problem 4: Factorial Calculation (Easy)
            [
                'title' => 'Factorial Calculation',
                'description' => 'Calculate the factorial of a given non-negative integer N. The factorial of a non-negative integer N, denoted by N!, is the product of all positive integers less than or equal to N. 0! is defined as 1.',
                'input_format' => 'A single integer N.',
                'output_format' => 'A single integer, the factorial of N.',
                'constraints' => '0 <= N <= 20',
                'sample_input' => '7',
                'sample_output' => '5040',
                'explanation' => '7! = 7 * 6 * 5 * 4 * 3 * 2 * 1 = 5040.',
                'difficulty' => 'easy',
                'created_by' => 1,
            ],
            // Problem 5: Find the Kth Largest Element (Medium)
            [
                'title' => 'Find the Kth Largest Element',
                'description' => 'Given an unsorted array of integers and an integer K, find the K-th largest element in the array. Note that it is the K-th largest element in the sorted order, not the K-th distinct element.',
                'input_format' => 'The first line contains an integer N, the number of elements in the array.' . "\n" . 'The second line contains N space-separated integers representing the array elements.' . "\n" . 'The third line contains an integer K.',
                'output_format' => 'A single integer, the K-th largest element.',
                'constraints' => '1 <= N <= 10^5' . "\n" . '-10^9 <= Array elements <= 10^9' . "\n" . '1 <= K <= N',
                'sample_input' => "8\n10 7 8 9 1 5 2 3\n3",
                'sample_output' => '8',
                'explanation' => 'Sorted array: 1, 2, 3, 5, 7, 8, 9, 10. The 3rd largest element is 8.',
                'difficulty' => 'medium',
                'created_by' => 1,
            ],
            // Problem 6: Reverse a String (Easy)
            [
                'title' => 'Reverse a String',
                'description' => 'Given a string, reverse it.',
                'input_format' => 'A single line containing a string S.',
                'output_format' => 'The reversed string.',
                'constraints' => '1 <= |S| <= 1000',
                'sample_input' => 'hello',
                'sample_output' => 'olleh',
                'explanation' => 'The reverse of "hello" is "olleh".',
                'difficulty' => 'easy',
                'created_by' => 1,
            ],
            // Problem 7: Check Prime (Medium)
            [
                'title' => 'Check Prime',
                'description' => 'Given a positive integer N, determine if it is a prime number.',
                'input_format' => 'A single integer N.',
                'output_format' => 'Print "YES" if N is prime, otherwise print "NO".',
                'constraints' => '1 <= N <= 10^7',
                'sample_input' => '17',
                'sample_output' => 'YES',
                'explanation' => '17 is only divisible by 1 and 17, so it is a prime number.',
                'difficulty' => 'medium',
                'created_by' => 1,
            ],
            // Problem 8: Fibonacci Sequence (Medium)
            [
                'title' => 'Fibonacci Sequence',
                'description' => 'Given an integer N, return the N-th Fibonacci number. The Fibonacci sequence starts with F_0 = 0, F_1 = 1, and F_n = F_{n-1} + F_{n-2} for n > 1.',
                'input_format' => 'A single integer N.',
                'output_format' => 'The N-th Fibonacci number.',
                'constraints' => '0 <= N <= 40',
                'sample_input' => '10',
                'sample_output' => '55',
                'explanation' => 'The Fibonacci sequence is 0, 1, 1, 2, 3, 5, 8, 13, 21, 34, 55,... The 10th number (0-indexed) is 55.',
                'difficulty' => 'medium',
                'created_by' => 1,
            ],
            // Problem 9: Array Sum (Easy)
            [
                'title' => 'Array Sum',
                'description' => 'Given an array of integers, find the sum of its elements.',
                'input_format' => 'The first line contains an integer N, the number of elements in the array.' . "\n" . 'The second line contains N space-separated integers representing the array elements.',
                'output_format' => 'A single integer, the sum of the array elements.',
                'constraints' => '1 <= N <= 10^3' . "\n" . '-10^6 <= Array elements <= 10^6',
                'sample_input' => "5\n1 2 3 4 5",
                'sample_output' => '15',
                'explanation' => '1 + 2 + 3 + 4 + 5 = 15.',
                'difficulty' => 'easy',
                'created_by' => 1,
            ],
            // Problem 10: Longest Common Prefix (Hard)
            [
                'title' => 'Longest Common Prefix',
                'description' => 'Write a function to find the longest common prefix string amongst an array of strings. If there is no common prefix, return an empty string "".',
                'input_format' => 'The first line contains an integer N, the number of strings.' . "\n" . 'The following N lines each contain a string S_i.',
                'output_format' => 'A single string, the longest common prefix.',
                'constraints' => '1 <= N <= 200' . "\n" . '0 <= |S_i| <= 200' . "\n" . 'All strings consist of lowercase English letters.',
                'sample_input' => "3\nflower\nflow\nflight",
                'sample_output' => 'fl',
                'explanation' => 'The longest common prefix among "flower", "flow", and "flight" is "fl".',
                'difficulty' => 'hard',
                'created_by' => 1,
            ],
        ];

        foreach ($problems as $problemData) {
            $problem = Problem::create($problemData);

            // Create a corresponding sample test case
            TestCase::create([
                'problem_id' => $problem->id,
                'input' => $problemData['sample_input'],
                'expected_output' => $problemData['sample_output'],
                'is_sample' => 1, // Mark as sample
                'points' => 0, // Points are typically for hidden test cases, set to 0 for samples
            ]);

            // Add additional hidden test cases here for each problem
            // For brevity, I'm only adding one hidden test case for each problem.
            // You should add more comprehensive hidden test cases for robust judging.
            switch ($problem->title) {
                case 'Sum of Two Numbers':
                    TestCase::create(['problem_id' => $problem->id, 'input' => '10 20', 'expected_output' => '30', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => '-5 5', 'expected_output' => '0', 'is_sample' => 0, 'points' => 50]);
                    break;
                case 'Palindrome Checker':
                    TestCase::create(['problem_id' => $problem->id, 'input' => 'No lemon, no melon', 'expected_output' => 'YES', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => 'Google', 'expected_output' => 'NO', 'is_sample' => 0, 'points' => 50]);
                    break;
                case 'Smallest Missing Positive Integer':
                    TestCase::create(['problem_id' => $problem->id, 'input' => "4\n1 2 3 4", 'expected_output' => '5', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => "5\n-5 -2 0 100 200", 'expected_output' => '1', 'is_sample' => 0, 'points' => 50]);
                    break;
                case 'Factorial Calculation':
                    TestCase::create(['problem_id' => $problem->id, 'input' => '0', 'expected_output' => '1', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => '1', 'expected_output' => '1', 'is_sample' => 0, 'points' => 50]);
                    break;
                case 'Find the Kth Largest Element':
                    TestCase::create(['problem_id' => $problem->id, 'input' => "5\n1 2 3 4 5\n3", 'expected_output' => '3', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => "4\n10 10 10 10\n2", 'expected_output' => '10', 'is_sample' => 0, 'points' => 50]);
                    break;
                case 'Reverse a String':
                    TestCase::create(['problem_id' => $problem->id, 'input' => 'programming', 'expected_output' => 'gnimmargorp', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => '12345', 'expected_output' => '54321', 'is_sample' => 0, 'points' => 50]);
                    break;
                case 'Check Prime':
                    TestCase::create(['problem_id' => $problem->id, 'input' => '2', 'expected_output' => 'YES', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => '4', 'expected_output' => 'NO', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => '9999991', 'expected_output' => 'YES', 'is_sample' => 0, 'points' => 50]); // A large prime
                    break;
                case 'Fibonacci Sequence':
                    TestCase::create(['problem_id' => $problem->id, 'input' => '0', 'expected_output' => '0', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => '1', 'expected_output' => '1', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => '20', 'expected_output' => '6765', 'is_sample' => 0, 'points' => 50]);
                    break;
                case 'Array Sum':
                    TestCase::create(['problem_id' => $problem->id, 'input' => "3\n-1 -2 -3", 'expected_output' => '-6', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => "1\n1000000", 'expected_output' => '1000000', 'is_sample' => 0, 'points' => 50]);
                    break;
                case 'Longest Common Prefix':
                    TestCase::create(['problem_id' => $problem->id, 'input' => "2\napple\napricot", 'expected_output' => 'ap', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => "3\ndog\nracecar\ncar", 'expected_output' => '', 'is_sample' => 0, 'points' => 50]);
                    TestCase::create(['problem_id' => $problem->id, 'input' => "1\nhello", 'expected_output' => 'hello', 'is_sample' => 0, 'points' => 50]);
                    break;
            }
        }
    }
}