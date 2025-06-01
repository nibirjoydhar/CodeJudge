<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $contest->title }}
            </h2>
            <div class="flex items-center space-x-4">
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $status === 'Running' ? 'bg-green-100 text-green-800' : 
                       ($status === 'Upcoming' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ $status }}
                </span>
                @if (!$isParticipant && $status !== 'Ended')
                    <button onclick="document.getElementById('joinModal').classList.remove('hidden')" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Join Contest
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <x-contest-navigation :contest="$contest" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Contest Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Contest Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Start Time (Bangladesh):</p>
                                <p class="font-medium">{{ $contest->start_time->setTimezone('Asia/Dhaka')->format('F j, Y, g:i a') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">End Time (Bangladesh):</p>
                                <p class="font-medium">{{ $contest->end_time->setTimezone('Asia/Dhaka')->format('F j, Y, g:i a') }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-gray-600">Description:</p>
                                <p class="mt-1">{{ $contest->description }}</p>
                            </div>
                        </div>
                    </div>

                    @if($isParticipant)
                        @if($status === 'Upcoming')
                            <div class="text-center py-8">
                                <p class="text-gray-600">Contest has not started yet. Please wait until the start time.</p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Starting in: {{ Carbon\Carbon::now()->diffForHumans($contest->start_time->setTimezone('Asia/Dhaka'), ['parts' => 2]) }}
                                </p>
                            </div>
                        @else
                            <!-- Problems Tab -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4">Problems</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="py-2 px-4 text-left">#</th>
                                                <th class="py-2 px-4 text-left">Title</th>
                                                <th class="py-2 px-4 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contest->problems as $index => $problem)
                                                <tr class="border-b">
                                                    <td class="py-2 px-4">{{ chr(65 + $index) }}</td>
                                                    <td class="py-2 px-4">{{ $problem->title }}</td>
                                                    <td class="py-2 px-4 text-center">
                                                        <a href="{{ route('contests.problems.show', [$contest, $problem]) }}" 
                                                            class="text-blue-600 hover:text-blue-900 hover:underline">
                                                            View Problem
                                                        </a>
                                                        @if($status === 'Running')
                                                            <span class="mx-2">|</span>
                                                            <a href="{{ route('contests.problems.submit', [$contest, $problem]) }}"
                                                                class="text-green-600 hover:text-green-900 hover:underline">
                                                                Submit Solution
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Rankings Tab -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Rankings</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="py-2 px-4 text-left">Rank</th>
                                                <th class="py-2 px-4 text-left">Participant</th>
                                                <th class="py-2 px-4 text-center">Solved</th>
                                                <th class="py-2 px-4 text-center">Points</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rankings as $index => $participant)
                                                <tr class="border-b {{ $participant->id === auth()->id() ? 'bg-yellow-50' : '' }}">
                                                    <td class="py-2 px-4">{{ $index + 1 }}</td>
                                                    <td class="py-2 px-4">{{ $participant->name }}</td>
                                                    <td class="py-2 px-4 text-center">{{ $participant->accepted_count }}</td>
                                                    <td class="py-2 px-4 text-center">{{ $participant->total_points ?? 0 }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @elseif($status === 'Ended')
                        <div class="text-center py-8">
                            <p class="text-gray-600">This contest has ended.</p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-600">Please join the contest to view problems and participate.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Join Contest Modal -->
    <div id="joinModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Join Contest</h3>
                <form method="POST" action="{{ route('contests.join', $contest) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Contest Password</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('joinModal').classList.add('hidden')"
                            class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            Join
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 