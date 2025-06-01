<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $problem->title }}
            </h2>
            @if($contest->getStatus() === 'Running')
                <a href="{{ route('contests.problems.submit', [$contest, $problem]) }}" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150">
                    Submit Solution
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-8">
                        <h3 class="text-lg font-bold mb-4">Problem Description</h3>
                        <div class="prose max-w-none">
                            {!! nl2br(e($problem->description)) !!}
                        </div>
                    </div>

                    @if($problem->input_format)
                        <div class="mb-8">
                            <h3 class="text-lg font-bold mb-4">Input Format</h3>
                            <div class="prose max-w-none">
                                {!! nl2br(e($problem->input_format)) !!}
                            </div>
                        </div>
                    @endif

                    @if($problem->output_format)
                        <div class="mb-8">
                            <h3 class="text-lg font-bold mb-4">Output Format</h3>
                            <div class="prose max-w-none">
                                {!! nl2br(e($problem->output_format)) !!}
                            </div>
                        </div>
                    @endif

                    @if($problem->constraints)
                        <div class="mb-8">
                            <h3 class="text-lg font-bold mb-4">Constraints</h3>
                            <div class="prose max-w-none">
                                {!! nl2br(e($problem->constraints)) !!}
                            </div>
                        </div>
                    @endif

                    <div class="mb-8">
                        <h3 class="text-lg font-bold mb-4">Sample Test Cases</h3>
                        <div class="space-y-4">
                            @foreach($problem->formatted_test_cases as $index => $test_case)
                                <div class="border rounded-lg overflow-hidden">
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
                                                <pre id="input-{{ $index }}" class="text-sm font-mono whitespace-pre-wrap">{{ $test_case['input'] ?? 'N/A' }}</pre>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <div class="font-medium text-sm text-gray-600">Output</div>
                                                <button onclick="copyToClipboard('output-{{ $index }}')" class="copy-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded shadow-sm flex items-center text-xs transition-all duration-200 hover:scale-105 hover:shadow-md">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                    </svg>
                                                    <span>{{ __('Copy') }}</span>
                                                </button>
                                            </div>
                                            <div class="bg-gray-50 p-3 rounded border">
                                                <pre id="output-{{ $index }}" class="text-sm font-mono whitespace-pre-wrap">{{ $test_case['output'] ?? 'N/A' }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                    @if(isset($test_case['explanation']))
                                        <div class="border-t p-4">
                                            <div class="font-medium text-sm text-gray-600 mb-2">Explanation</div>
                                            <div class="text-sm text-gray-700">
                                                {!! nl2br(e($test_case['explanation'])) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($problem->explanation)
                        <div class="mb-8">
                            <h3 class="text-lg font-bold mb-4">Explanation</h3>
                            <div class="prose max-w-none">
                                {!! nl2br(e($problem->explanation)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            
            navigator.clipboard.writeText(text).then(() => {
                const button = element.parentElement.parentElement.querySelector('.copy-btn');
                const originalText = button.querySelector('span').textContent;
                button.querySelector('span').textContent = 'Copied!';
                setTimeout(() => {
                    button.querySelector('span').textContent = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }
    </script>
    @endpush
</x-app-layout> 