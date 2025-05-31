<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $contest->title }}
            </h2>
            @if(!$isParticipant && !$contest->hasEnded())
                <button onclick="document.getElementById('joinModal').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Join Contest
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Contest Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Contest Details</h3>
                            <p><strong>Created by:</strong> {{ $contest->creator->name }}</p>
                            <p><strong>Start Time:</strong> {{ $contest->start_time->format('Y-m-d H:i') }}</p>
                            <p><strong>End Time:</strong> {{ $contest->end_time->format('Y-m-d H:i') }}</p>
                            <p><strong>Status:</strong>
                                @if($contest->hasEnded())
                                    <span class="text-gray-600">Ended</span>
                                @elseif($contest->isActive())
                                    <span class="text-green-600">Active</span>
                                @else
                                    <span class="text-yellow-600">Upcoming</span>
                                @endif
                            </p>
                        </div>
                        @if($contest->description)
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Description</h3>
                                <p class="text-gray-600">{{ $contest->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($isParticipant || $contest->hasEnded())
                <!-- Problems and Rankings Tabs -->
                <div x-data="{ tab: 'problems' }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            <button @click="tab = 'problems'" :class="{ 'border-blue-500 text-blue-600': tab === 'problems' }" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                                Problems
                            </button>
                            <button @click="tab = 'submissions'" :class="{ 'border-blue-500 text-blue-600': tab === 'submissions' }" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                                Submissions
                            </button>
                            <button @click="tab = 'rankings'" :class="{ 'border-blue-500 text-blue-600': tab === 'rankings' }" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                                Rankings
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        <!-- Problems Tab -->
                        <div x-show="tab === 'problems'">
                            <div class="grid gap-4">
                                @foreach($contest->problems as $problem)
                                    <div class="border rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-semibold">{{ $problem->title }}</h3>
                                                <p class="text-sm text-gray-600">{{ Str::limit($problem->description, 200) }}</p>
                                            </div>
                                            @if($isParticipant && $contest->isActive())
                                                <a href="{{ route('problems.show', $problem) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                    Solve
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Submissions Tab -->
                        <div x-show="tab === 'submissions'" class="overflow-x-auto">
                            @if($submissions->isEmpty())
                                <p class="text-center text-gray-500">No submissions yet.</p>
                            @else
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Problem</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Language</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verdict</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($submissions as $submission)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $submission->user->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $submission->problem->title }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $submission->language }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span class="px-2 py-1 rounded text-sm
                                                        @if($submission->verdict === 'Accepted') bg-green-200 text-green-800
                                                        @elseif($submission->verdict === 'Wrong Answer') bg-red-200 text-red-800
                                                        @else bg-yellow-200 text-yellow-800
                                                        @endif">
                                                        {{ $submission->verdict }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $submission->created_at->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- Rankings Tab -->
                        <div x-show="tab === 'rankings'" class="overflow-x-auto">
                            @if($rankings->isEmpty())
                                <p class="text-center text-gray-500">No participants yet.</p>
                            @else
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solved</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($rankings as $index => $participant)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $participant->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $participant->accepted_count }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Join Contest Modal -->
    <div id="joinModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('contests.join', $contest) }}">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Join Contest
                                </h3>
                                <div class="mt-2">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">Contest Password</label>
                                        <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Join
                        </button>
                        <button type="button" onclick="document.getElementById('joinModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 