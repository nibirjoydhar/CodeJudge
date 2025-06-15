<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Problem') }}
            </h2>
            <a href="{{ route('admin.problems.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.problems.store') }}" id="createProblemForm">
                        @csrf

                        @if($errors->any())
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror" required maxlength="255">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Format -->
                        <div class="mb-4">
                            <label for="input_format" class="block text-sm font-medium text-gray-700">Input Format</label>
                            <textarea name="input_format" id="input_format" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('input_format') border-red-500 @enderror" required>{{ old('input_format') }}</textarea>
                            @error('input_format')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Output Format -->
                        <div class="mb-4">
                            <label for="output_format" class="block text-sm font-medium text-gray-700">Output Format</label>
                            <textarea name="output_format" id="output_format" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('output_format') border-red-500 @enderror" required>{{ old('output_format') }}</textarea>
                            @error('output_format')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Constraints -->
                        <div class="mb-4">
                            <label for="constraints" class="block text-sm font-medium text-gray-700">Constraints</label>
                            <textarea name="constraints" id="constraints" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('constraints') border-red-500 @enderror" required>{{ old('constraints') }}</textarea>
                            @error('constraints')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sample Input -->
                        <div class="mb-4">
                            <label for="sample_input" class="block text-sm font-medium text-gray-700">Sample Input</label>
                            <textarea name="sample_input" id="sample_input" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('sample_input') border-red-500 @enderror" required>{{ old('sample_input') }}</textarea>
                            @error('sample_input')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sample Output -->
                        <div class="mb-4">
                            <label for="sample_output" class="block text-sm font-medium text-gray-700">Sample Output</label>
                            <textarea name="sample_output" id="sample_output" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('sample_output') border-red-500 @enderror" required>{{ old('sample_output') }}</textarea>
                            @error('sample_output')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Explanation -->
                        <div class="mb-4">
                            <label for="explanation" class="block text-sm font-medium text-gray-700">Explanation (Optional)</label>
                            <textarea name="explanation" id="explanation" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('explanation') border-red-500 @enderror">{{ old('explanation') }}</textarea>
                            @error('explanation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Difficulty -->
                        <div class="mb-4">
                            <label for="difficulty" class="block text-sm font-medium text-gray-700">Difficulty</label>
                            <select name="difficulty" id="difficulty" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('difficulty') border-red-500 @enderror" required>
                                <option value="">Select difficulty</option>
                                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                            @error('difficulty')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Test Cases -->
                        <div class="mb-4">
                            <label for="test_cases" class="block text-sm font-medium text-gray-700">Test Cases</label>
                            <div id="test_cases_container" class="space-y-4">
                                <div class="test-case border rounded p-4 bg-gray-50">
                                    <div class="mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Input</label>
                                        <textarea name="test_cases[0][input]" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono bg-white" required>{{ old('test_cases.0.input') }}</textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Expected Output</label>
                                        <textarea name="test_cases[0][expected_output]" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono bg-white" required>{{ old('test_cases.0.expected_output') }}</textarea>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="test_cases[0][is_sample]" value="1" {{ old('test_cases.0.is_sample') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600">Sample Test Case</span>
                                        </label>
                                        <div>
                                            <label class="text-sm text-gray-600">Points</label>
                                            <input type="number" name="test_cases[0][points]" value="{{ old('test_cases.0.points', 0) }}" min="0" class="ml-2 w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="addTestCase()" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Add Test Case
                            </button>
                        </div>

                        <script>
                            function addTestCase() {
                                const container = document.getElementById('test_cases_container');
                                const index = container.children.length;
                                const template = `
                                    <div class="test-case border rounded p-4 bg-gray-50">
                                        <div class="mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Input</label>
                                            <textarea name="test_cases[\${index}][input]" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono bg-white" required></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Expected Output</label>
                                            <textarea name="test_cases[\${index}][expected_output]" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono bg-white" required></textarea>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="test_cases[\${index}][is_sample]" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-600">Sample Test Case</span>
                                            </label>
                                            <div>
                                                <label class="text-sm text-gray-600">Points</label>
                                                <input type="number" name="test_cases[\${index}][points]" value="0" min="0" class="ml-2 w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                            </div>
                                        </div>
                                    </div>
                                `;
                                container.insertAdjacentHTML('beforeend', template);
                            }

                            // Add form submission validation
                            document.getElementById('createProblemForm').addEventListener('submit', function(e) {
                                const testCases = document.querySelectorAll('.test-case');
                                if (testCases.length === 0) {
                                    e.preventDefault();
                                    alert('Please add at least one test case.');
                                    return false;
                                }
                                return true;
                            });
                        </script>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create Problem') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 