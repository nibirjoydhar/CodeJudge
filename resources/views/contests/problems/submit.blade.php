<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Submit Solution: {{ $problem->title }}
            </h2>
            <a href="{{ route('contests.problems.show', [$contest, $problem]) }}" 
                class="text-blue-600 hover:text-blue-900 hover:underline">
                Back to Problem
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('contests.problems.submit.store', [$contest, $problem]) }}">
                        @csrf

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
                            <textarea id="code" name="code" required rows="15"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 font-mono"
                                placeholder="Write your code here...">{{ old('code') }}</textarea>
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('contests.problems.show', [$contest, $problem]) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Submit Solution') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @if(session('success') || session('error'))
                        <div class="mt-6 p-4 rounded-md {{ session('success') ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}">
                            {{ session('success') ?? session('error') }}
                        </div>
                    @endif

                    <!-- Recent Submissions -->
                    @if($contest->submissions()->where('user_id', auth()->id())->where('problem_id', $problem->id)->exists())
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-semibold mb-4">Your Recent Submissions for this Problem</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Language</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($contest->submissions()->where('user_id', auth()->id())->where('problem_id', $problem->id)->latest()->take(5)->get() as $submission)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $submission->created_at->diffForHumans() }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $submission->getLanguageName() }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $submission->status === 'Accepted' ? 'bg-green-100 text-green-800' : 
                                                        ($submission->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $submission->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 