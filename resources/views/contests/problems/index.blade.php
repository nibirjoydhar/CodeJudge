<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $contest->title }} - Problems
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
                                    <th class="py-3 px-6 text-left">#</th>
                                    <th class="py-3 px-6 text-left">Title</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($problems as $index => $problem)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-4 px-6">{{ chr(65 + $index) }}</td>
                                        <td class="py-4 px-6">{{ $problem->title }}</td>
                                        <td class="py-4 px-6 text-center">
                                            <a href="{{ route('contests.problems.show', [$contest, $problem]) }}" 
                                               class="text-blue-600 hover:text-blue-900 hover:underline">
                                                View Problem
                                            </a>
                                            @if($contest->getStatus() === 'Running')
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
            </div>
        </div>
    </div>
</x-app-layout> 