<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $contest->title }} - Standings
        </h2>
    </x-slot>

    <x-contest-navigation :contest="$contest" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-3 px-6 text-left">Rank</th>
                                    <th class="py-3 px-6 text-left">Participant</th>
                                    <th class="py-3 px-6 text-center">Solved</th>
                                    <th class="py-3 px-6 text-center">Total Points</th>
                                    <th class="py-3 px-6 text-center">Total Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rankings as $index => $participant)
                                    <tr class="border-b hover:bg-gray-50 {{ $participant->id === auth()->id() ? 'bg-yellow-50' : '' }}">
                                        <td class="py-4 px-6">{{ $index + 1 }}</td>
                                        <td class="py-4 px-6">{{ $participant->name }}</td>
                                        <td class="py-4 px-6 text-center">{{ $participant->solved_count }}</td>
                                        <td class="py-4 px-6 text-center">{{ $participant->total_points }}</td>
                                        <td class="py-4 px-6 text-center">{{ $participant->total_time }} minutes</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 