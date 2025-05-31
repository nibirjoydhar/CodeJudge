<?php

namespace Database\Seeders;

use App\Models\Problem;
use App\Models\TestCase;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProblemSeeder extends Seeder
{
    public function run()
    {
        // First, make sure we have an admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@codejudge.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password123'),
                'role' => 'admin',
            ]
        );

        $problems = [
            [
                'title' => 'Hello World',
                'description' => 'Write a program that prints "Hello, World!" to the output.',
                'input_format' => 'There is no input for this problem.',
                'output_format' => 'Print exactly: Hello, World!',
                'constraints' => 'Output should match exactly, including punctuation.',
                'sample_input' => '',
                'sample_output' => 'Hello, World!',
                'explanation' => 'This is a classic first program to get started with programming.',
                'difficulty' => 'easy',
                'test_cases' => [
                    [
                        'input' => '',
                        'expected_output' => 'Hello, World!',
                        'is_sample' => true,
                        'points' => 100
                    ]
                ]
            ],
            [
                'title' => 'Sum of Two Numbers',
                'description' => 'Given two integers A and B, find their sum.',
                'input_format' => "First line contains two space-separated integers A and B.\nConstraints:\n-1000 ≤ A, B ≤ 1000",
                'output_format' => 'Print a single integer - the sum of A and B.',
                'constraints' => '-1000 ≤ A, B ≤ 1000',
                'sample_input' => "2 3",
                'sample_output' => "5",
                'explanation' => 'For the first test case: 2 + 3 = 5',
                'difficulty' => 'easy',
                'test_cases' => [
                    [
                        'input' => '2 3',
                        'expected_output' => '5',
                        'is_sample' => true,
                        'points' => 20
                    ],
                    [
                        'input' => '-5 10',
                        'expected_output' => '5',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => '0 0',
                        'expected_output' => '0',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => '1000 -1000',
                        'expected_output' => '0',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => '-999 999',
                        'expected_output' => '0',
                        'is_sample' => false,
                        'points' => 20
                    ]
                ]
            ],
            [
                'title' => 'Even or Odd',
                'description' => 'Given an integer N, determine if it is even or odd.',
                'input_format' => 'Single integer N',
                'output_format' => 'Print "Even" if N is even, "Odd" if N is odd (without quotes).',
                'constraints' => '-10^9 ≤ N ≤ 10^9',
                'sample_input' => "4",
                'sample_output' => "Even",
                'explanation' => '4 is divisible by 2, so it\'s Even',
                'difficulty' => 'easy',
                'test_cases' => [
                    [
                        'input' => '4',
                        'expected_output' => 'Even',
                        'is_sample' => true,
                        'points' => 20
                    ],
                    [
                        'input' => '7',
                        'expected_output' => 'Odd',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => '0',
                        'expected_output' => 'Even',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => '-5',
                        'expected_output' => 'Odd',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => '1000000000',
                        'expected_output' => 'Even',
                        'is_sample' => false,
                        'points' => 20
                    ]
                ]
            ],
            [
                'title' => 'Reverse String',
                'description' => 'Write a program to reverse a given string.',
                'input_format' => 'Single line containing a string S consisting of lowercase English letters.',
                'output_format' => 'Print the string S in reverse order.',
                'constraints' => '1 ≤ length of S ≤ 100',
                'sample_input' => "hello",
                'sample_output' => "olleh",
                'explanation' => 'Simply print the characters in reverse order.',
                'difficulty' => 'easy',
                'test_cases' => [
                    [
                        'input' => 'hello',
                        'expected_output' => 'olleh',
                        'is_sample' => true,
                        'points' => 20
                    ],
                    [
                        'input' => 'codejudge',
                        'expected_output' => 'egdujedoc',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => 'a',
                        'expected_output' => 'a',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => 'racecar',
                        'expected_output' => 'racecar',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => 'abcdefghijklmnopqrstuvwxyz',
                        'expected_output' => 'zyxwvutsrqponmlkjihgfedcba',
                        'is_sample' => false,
                        'points' => 20
                    ]
                ]
            ],
            [
                'title' => 'Count Vowels',
                'description' => 'Given a string, count the number of vowels (a, e, i, o, u) in it.',
                'input_format' => 'Single line containing a string S consisting of lowercase English letters.',
                'output_format' => 'Print a single integer - the count of vowels in the string.',
                'constraints' => '1 ≤ length of S ≤ 100',
                'sample_input' => "hello",
                'sample_output' => "2",
                'explanation' => 'hello has two vowels: e and o',
                'difficulty' => 'easy',
                'test_cases' => [
                    [
                        'input' => 'hello',
                        'expected_output' => '2',
                        'is_sample' => true,
                        'points' => 20
                    ],
                    [
                        'input' => 'aeiou',
                        'expected_output' => '5',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => 'xyz',
                        'expected_output' => '0',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => 'programming',
                        'expected_output' => '3',
                        'is_sample' => false,
                        'points' => 20
                    ],
                    [
                        'input' => 'aeiouplmnaeiouplmn',
                        'expected_output' => '10',
                        'is_sample' => false,
                        'points' => 20
                    ]
                ]
            ]
        ];

        foreach ($problems as $problemData) {
            $testCases = $problemData['test_cases'];
            unset($problemData['test_cases']);
            
            $problem = Problem::create(array_merge($problemData, ['created_by' => $admin->id]));
            
            foreach ($testCases as $testCase) {
                TestCase::create(array_merge($testCase, ['problem_id' => $problem->id]));
            }
        }
    }
} 