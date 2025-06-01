<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('My Submissions') }}
            </h2>
            <a href="{{ route('submissions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-600 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('New Submission') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="w-1/12 py-2 px-4 text-left">#</th>
                                    <th class="w-3/12 py-2 px-4 text-left">Problem</th>
                                    <th class="w-1/12 py-2 px-4 text-left">Language</th>
                                    <th class="w-4/12 py-2 px-4 text-left">Status</th>
                                    <th class="w-2/12 py-2 px-4 text-left">Submitted</th>
                                    <th class="w-1/12 py-2 px-4 text-center">Actions</th>
                                    </tr>
                                </thead>
                            <tbody>
                                @forelse($submissions as $submission)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $submission->id }}</td>
                                        <td class="py-2 px-4">
                                                <a href="{{ route('problems.show', $submission->problem) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                                    {{ $submission->problem->title }}
                                                </a>
                                            </td>
                                        <td class="py-2 px-4">{{ $submission->getLanguageName() }}</td>
                                        <td class="py-2 px-4">
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $submission->status === 'Accepted' ? 'bg-green-100 text-green-800' : 
                                                      ($submission->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    @if(str_starts_with($submission->status, 'Compilation Error'))
                                                        Compilation Error
                                                    @elseif(strlen($submission->status) > 50)
                                                        {{ substr($submission->status, 0, 50) }}...
                                                    @else
                                                    {{ $submission->status }}
                                                    @endif
                                                </span>
                                            </div>
                                            </td>
                                        <td class="py-2 px-4">{{ $submission->created_at->diffForHumans() }}</td>
                                        <td class="py-2 px-4 text-center">
                                            <a href="{{ route('submissions.show', $submission) }}" 
                                                class="text-xs text-blue-600 hover:text-blue-900 hover:underline">
                                                View Details
                                            </a>
                                            </td>
                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-4 text-center">No submissions found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        <div class="mt-4">
                            {{ $submissions->links() }}
                        </div>
                        </div>
                </div>
            </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
        </div>
    </div>
</x-app-layout> 