<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $contest->title }} - Submissions
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
                                    <th class="py-3 px-6 text-left">ID</th>
                                    <th class="py-3 px-6 text-left">Problem</th>
                                    <th class="py-3 px-6 text-left">User</th>
                                    <th class="py-3 px-6 text-center">Language</th>
                                    <th class="py-3 px-6 text-center">Status</th>
                                    <th class="py-3 px-6 text-center">Time</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $submission)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-4 px-6">#{{ $submission->id }}</td>
                                        <td class="py-4 px-6">
                                            <a href="{{ route('contests.problems.show', [$contest, $submission->problem]) }}" 
                                               class="text-blue-600 hover:text-blue-900 hover:underline">
                                                {{ $submission->problem->title }}
                                            </a>
                                        </td>
                                        <td class="py-4 px-6">{{ $submission->user->name }}</td>
                                        <td class="py-4 px-6 text-center">{{ $submission->language }}</td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="px-2 py-1 rounded text-sm font-semibold
                                                {{ $submission->status === 'Accepted' ? 'bg-green-100 text-green-800' : 
                                                   ($submission->status === 'Wrong Answer' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800') }}">
                                                {{ $submission->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-center">{{ $submission->created_at->diffForHumans() }}</td>
                                        <td class="py-4 px-6 text-center">
                                            <a href="{{ route('submissions.show', $submission) }}" 
                                               class="text-blue-600 hover:text-blue-900 hover:underline">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $submissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 