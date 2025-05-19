<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Problems') }}
            </h2>
            <a href="{{ route('admin.problems.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Add New Problem') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="w-1/12 py-2 px-4 text-left">#</th>
                                    <th class="w-6/12 py-2 px-4 text-left">Title</th>
                                    <th class="w-2/12 py-2 px-4 text-left">Test Cases</th>
                                    <th class="w-3/12 py-2 px-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($problems as $problem)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $problem->id }}</td>
                                        <td class="py-2 px-4">{{ $problem->title }}</td>
                                        <td class="py-2 px-4">{{ count($problem->formatted_test_cases) }}</td>
                                        <td class="py-2 px-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('admin.problems.edit', $problem) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.problems.destroy', $problem) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded" onclick="return confirm('Are you sure you want to delete this problem?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center">No problems found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 