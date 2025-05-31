<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contests') }}
            </h2>
            <a href="{{ route('contests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Contest
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($contests->isEmpty())
                        <p class="text-gray-500 text-center">No contests available.</p>
                    @else
                        <div class="grid gap-4">
                            @foreach($contests as $contest)
                                <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold">
                                                <a href="{{ route('contests.show', $contest) }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $contest->title }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-600">Created by: {{ $contest->creator->name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm">
                                                Start: {{ $contest->start_time->format('Y-m-d H:i') }}
                                            </p>
                                            <p class="text-sm">
                                                End: {{ $contest->end_time->format('Y-m-d H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($contest->description)
                                        <p class="mt-2 text-gray-600">{{ Str::limit($contest->description, 150) }}</p>
                                    @endif
                                    <div class="mt-2">
                                        @if($contest->hasEnded())
                                            <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded text-sm">Ended</span>
                                        @elseif($contest->isActive())
                                            <span class="px-2 py-1 bg-green-200 text-green-700 rounded text-sm">Active</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-200 text-yellow-700 rounded text-sm">Upcoming</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $contests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 