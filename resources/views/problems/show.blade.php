                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $problem->title }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('problems.list') }}"
                    class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                    Back to Problems
                </a>
                <a href="{{ route('comments.index', $problem) }}"
                    class="px-4 py-2 bg-green-500 rounded-md text-white hover:bg-green-600">
                    Discuss
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                @if(isset($submissions) && $submissions->count() > 0)
                <!-- Mobile-only Previous Submissions (hidden on md screens and up) -->
                <div class="md:hidden w-full">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 text-gray-900">
                            <h3 class="text-lg font-bold mb-4">My Previous Submissions</h3>
                            <div class="space-y-3">
                                @foreach($submissions as $submission)
                                    <div class="border rounded-md p-3 hover:bg-gray-50">
                                        <div class="flex justify-between items-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $submission->status === 'Accepted' ? 'bg-green-100 text-green-800' : 
                                                ($submission->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                @if(str_starts_with($submission->status, 'Compilation Error'))
                                                    Compilation Error
                                                @elseif(strlen($submission->status) > 50)
                                                    {{ substr($submission->status, 0, 50) }}...
                                                @else
                                                {{ $submission->status }}
                                                @endif
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $submission->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="mt-2 flex justify-between items-center">
                                            <span class="text-sm text-gray-600">{{ $submission->getLanguageName() }}</span>
                                            <a href="{{ route('submissions.show', $submission) }}" 
                                                class="text-xs text-blue-600 hover:text-blue-900 hover:underline">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="mt-2 text-center">
                                    <a href="{{ route('submissions.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                        View All Submissions
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Main Content Area with Right Sidebar -->
                <div style="display: flex; flex-direction: column; flex-wrap: nowrap; gap: 1.5rem; position: relative;">
                    <div style="display: flex; flex-direction: row; width: 100%;">
                        <!-- Main Content Area (problem description, test cases, submission form) -->
                        <div style="flex: 1; max-width: 100%; padding-right: 0;">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900">                                 
                                    <div class="mb-8">
                                        <h3 class="text-lg font-bold mb-4">Problem Description</h3>                                                         
                                        <div class="prose max-w-none">
                                            {!! nl2br(e($problem->description)) !!}                             
                                        </div>
                                    </div>                      

                                    <div class="mb-8">                                                                      
                                        <h3 class="text-lg font-bold mb-4">Test Cases</h3>
                                        <div class="grid grid-cols-1 gap-6">
                                            @foreach($problem->testCases->where('is_sample', true) as $index => $test_case)
                                                <div class="border rounded-lg shadow-sm overflow-hidden">
                                                    <div class="bg-gray-100 px-4 py-2 border-b">
                                                        <div class="flex justify-between items-center">
                                                            <h4 class="font-medium text-gray-700">Sample Test Case #{{ $index + 1 }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x">
                                                        <div class="p-4">
                                                            <div class="flex justify-between items-center mb-2">
                                                                <div class="font-medium text-sm text-gray-600">Input</div>
                                                                <button onclick="copyToClipboard('input-{{ $index }}')" class="copy-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded shadow-sm flex items-center text-xs transition-all duration-200 hover:scale-105 hover:shadow-md">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                                    </svg>
                                                                    <span>{{ __('Copy') }}</span>
                                                                </button>
                                                            </div>
                                                            <div class="bg-gray-50 p-3 rounded border">
                                                                <pre id="input-{{ $index }}" class="p-4 text-sm font-mono whitespace-pre-wrap">{!! e($test_case->input ?? 'N/A') !!}</pre>
                                                            </div>
                                                        </div>
                                                        <div class="p-4">
                                                            <div class="flex justify-between items-center mb-2">
                                                                <div class="font-medium text-sm text-gray-600">Expected Output</div>
                                                                <button onclick="copyToClipboard('output-{{ $index }}')" class="copy-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded shadow-sm flex items-center text-xs transition-all duration-200 hover:scale-105 hover:shadow-md">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                                    </svg>
                                                                    <span>{{ __('Copy') }}</span>
                                                                </button>
                                                            </div>
                                                            <div class="bg-gray-50 p-3 rounded border">
                                                                <pre id="output-{{ $index }}" class="p-4 text-sm font-mono whitespace-pre-wrap">{!! e($test_case->expected_output ?? 'N/A') !!}</pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mt-8 border-t pt-8">
                                        <h3 class="text-xl font-bold mb-6 text-center mt-4 text-blue-500">Submit Your Solution</h3>
                                        <form method="POST" action="{{ route('submissions.store') }}">
                                            @csrf

                                            <!-- Hidden Problem ID -->
                                            <input type="hidden" name="problem_id" value="{{ $problem->id }}">

                                            <!-- Language Selection -->
                                            <div class="mb-4">
                                                <x-input-label for="language_id" :value="__('Programming Language')" />
                                                <select id="language_id" name="language_id" required
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <option value="">Select a language</option>
                                                    <option value="54" {{ isset($latestSubmission) && $latestSubmission->language_id == 54 ? 'selected' : '' }}>C++</option>
                                                    <option value="71" {{ isset($latestSubmission) && $latestSubmission->language_id == 71 ? 'selected' : '' }}>Python</option>
                                                    <option value="62" {{ isset($latestSubmission) && $latestSubmission->language_id == 62 ? 'selected' : '' }}>Java</option>
                                                </select>
                                                <x-input-error :messages="$errors->get('language_id')" class="mt-2" />
                                            </div>

                                            <!-- Code -->
                                            <div class="mb-4">
                                                <x-input-label for="code" :value="__('Your Code')" />
                                                <textarea id="code" name="code" rows="15" required
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 font-mono">{{ old('code') ?? (isset($latestSubmission) ? $latestSubmission->code : '') }}</textarea>
                                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                                            </div>

                                            <div class="flex items-center justify-center mt-6">
                                                <x-primary-button class="px-6 py-3 text-lg">
                                                    {{ __('Submit Solution') }}
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Sidebar - Only visible on md screens and up -->
                        <div style="width: 300px; margin-left: 1.5rem;" class="md:block hidden" id="sidebar">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-8">
                                <div class="p-4 text-gray-900">
                                    <h3 class="text-lg font-bold mb-4">My Previous Submissions</h3>
                                    
                                    @if(count($submissions ?? []) > 0)
                                        <div class="space-y-3">
                                            @foreach($submissions as $submission)
                                                <div class="border rounded-md p-3 hover:bg-gray-50">
                                                    <div class="flex justify-between items-center">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $submission->status === 'Accepted' ? 'bg-green-100 text-green-800' : 
                                                            ($submission->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                            @if(str_starts_with($submission->status, 'Compilation Error'))
                                                                Compilation Error
                                                            @elseif(strlen($submission->status) > 50)
                                                                {{ substr($submission->status, 0, 50) }}...
                                                            @else
                                                            {{ $submission->status }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="mt-2 flex justify-between items-center">
                                                        <span class="text-sm text-gray-600">{{ $submission->getLanguageName() }}</span>
                                                        <a href="{{ route('submissions.show', $submission) }}" 
                                                            class="text-xs text-blue-600 hover:text-blue-900 hover:underline">
                                                            View Details
                                                        </a>
                                                    </div>
                                                    <div class="mt-1 text-xs text-gray-500">
                                                        {{ $submission->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            <div class="mt-2 text-center">
                                                <a href="{{ route('submissions.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                                    View All Submissions
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-gray-500 text-sm">
                                            You have not submitted any solutions for this problem yet.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            // Get the content to copy
            const element = document.getElementById(elementId);
            const textToCopy = element.textContent;
            const button = event.currentTarget;

            // Store original button text
            const buttonText = button.querySelector('span');
            const originalText = buttonText.textContent;
            
            // Copy text to clipboard
            navigator.clipboard.writeText(textToCopy)
                .then(() => {
                    // Change button style to green
                    button.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'text-gray-800');
                    button.classList.add('bg-green-500', 'text-white');
                    
                    // Change text to "Copied!"
                    buttonText.textContent = "Copied!";
                    
                    // Set a timeout to revert after 2 seconds
                    setTimeout(() => {
                        // Change back to original style
                        button.classList.remove('bg-green-500', 'text-white');
                        button.classList.add('bg-gray-200', 'hover:bg-gray-300', 'text-gray-800');
                        
                        // Change text back to original
                        buttonText.textContent = originalText;
                    }, 2000);
                })
                .catch(err => {
                    console.error('Could not copy text: ', err);
                    
                    // Change button style to red
                    button.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'text-gray-800');
                    button.classList.add('bg-red-500', 'text-white');
                    
                    // Change text to "Failed!"
                    buttonText.textContent = "Failed!";
                    
                    // Set a timeout to revert after 2 seconds
                    setTimeout(() => {
                        // Change back to original style
                        button.classList.remove('bg-red-500', 'text-white');
                        button.classList.add('bg-gray-200', 'hover:bg-gray-300', 'text-gray-800');
                        
                        // Change text back to original
                        buttonText.textContent = originalText;
                    }, 2000);
                });
        }
    </script>
</x-app-layout>