<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $problem->title }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('problems.list') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
                    Back to Problems
                </a>
                <a href="{{ route('comments.index', $problem) }}" class="px-4 py-2 bg-green-500 rounded-md text-white hover:bg-green-600">
                    Discuss
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Input
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Expected Output
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($problem->test_cases as $test_case)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-pre-wrap text-sm text-gray-500">
                                                {{ $test_case['input'] ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-pre-wrap text-sm text-gray-500">
                                                {{ $test_case['output'] ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-8 border-t pt-8">
                        <h3 class="text-xl font-bold mb-6 text-center">Submit Your Solution</h3>
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
                                    <option value="54">C++</option>
                                    <option value="71">Python</option>
                                    <option value="62">Java</option>
                                </select>
                                <x-input-error :messages="$errors->get('language_id')" class="mt-2" />
                            </div>

                            <!-- Code -->
                            <div class="mb-4">
                                <x-input-label for="code" :value="__('Your Code')" />
                                <textarea id="code" name="code" rows="15" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 font-mono"
                                >{{ old('code') }}</textarea>
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
    </div>
</x-app-layout> 