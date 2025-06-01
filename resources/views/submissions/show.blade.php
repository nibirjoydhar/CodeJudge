<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Submission Details') }}
            </h2>
            <a href="{{ route('submissions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-800 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Back to Submissions') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Basic Info -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Submission Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Problem') }}</p>
                                <p class="font-medium">
                                    <a href="{{ route('problems.show', $submission->problem) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                        {{ $submission->problem->title }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Language') }}</p>
                                <p class="font-medium">{{ $submission->getLanguageName() }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Submitted At') }}</p>
                                <p class="font-medium">{{ $submission->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Status') }}</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $submission->status === 'Accepted' ? 'bg-green-100 text-green-800' : 
                                        ($submission->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $submission->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Details -->
                    @if(str_starts_with($submission->status, 'Compilation Error') || strlen($submission->status) > 50)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Error Details') }}</h3>
                        <div class="bg-gray-50 rounded-lg p-4 font-mono text-sm overflow-x-auto">
                            <pre class="whitespace-pre-wrap">{{ $submission->status }}</pre>
                        </div>
                    </div>
                    @endif

                    <!-- Source Code -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">{{ __('Source Code') }}</h3>
                            <button onclick="copyToClipboard('sourceCode')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1.5 px-3 rounded shadow-sm flex items-center transition-all duration-200 hover:scale-105 hover:shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    <span>{{ __('Copy') }}</span>
                                </button>
                            </div>
                        <div class="bg-gray-50 rounded-lg p-4 font-mono text-sm overflow-x-auto">
                            <pre id="sourceCode" class="whitespace-pre-wrap">{{ $submission->code }}</pre>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Test Cases') }}</h3>
                        <div class="space-y-4">
                            @foreach($submission->problem->testCases as $index => $testCase)
                                <div class="border rounded-md p-4">
                                    <h4 class="font-medium mb-2">{{ __('Test Case') }} #{{ $index + 1 }}</h4>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-sm text-gray-600">{{ __('Input') }}</p>
                                            <button onclick="copyToClipboard('input-{{ $index }}')" class="copy-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded shadow-sm flex items-center text-xs transition-all duration-200 hover:scale-105 hover:shadow-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                </svg>
                                                <span>{{ __('Copy') }}</span>
                                            </button>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-md shadow-inner">
                                            <pre id="input-{{ $index }}" class="text-sm font-mono whitespace-pre-wrap">{{ $testCase->input ?? '' }}</pre>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-sm text-gray-600">{{ __('Expected Output') }}</p>
                                            <button onclick="copyToClipboard('output-{{ $index }}')" class="copy-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded shadow-sm flex items-center text-xs transition-all duration-200 hover:scale-105 hover:shadow-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                </svg>
                                                <span>{{ __('Copy') }}</span>
                                            </button>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-md shadow-inner">
                                            <pre id="output-{{ $index }}" class="text-sm font-mono whitespace-pre-wrap">{{ $testCase->expected_output ?? '' }}</pre>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const textToCopy = element.textContent;
            const button = event.currentTarget;
            const buttonText = button.querySelector('span');
            const originalText = buttonText.textContent;
            
            navigator.clipboard.writeText(textToCopy)
                .then(() => {
                    button.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'text-gray-800');
                    button.classList.add('bg-green-500', 'text-white');
                    buttonText.textContent = "Copied!";
                    
                    setTimeout(() => {
                        button.classList.remove('bg-green-500', 'text-white');
                        button.classList.add('bg-gray-200', 'hover:bg-gray-300', 'text-gray-800');
                        buttonText.textContent = originalText;
                    }, 2000);
                })
                .catch(err => {
                    console.error('Could not copy text: ', err);
                    button.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'text-gray-800');
                    button.classList.add('bg-red-500', 'text-white');
                    buttonText.textContent = "Failed!";
                    
                    setTimeout(() => {
                        button.classList.remove('bg-red-500', 'text-white');
                        button.classList.add('bg-gray-200', 'hover:bg-gray-300', 'text-gray-800');
                        buttonText.textContent = originalText;
                    }, 2000);
                });
        }
    </script>
</x-app-layout> 