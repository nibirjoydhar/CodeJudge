<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Submission Details') }}
            </h2>
            <a href="{{ route('submissions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-800 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Back to Submissions') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Submission Information') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Problem') }}</p>
                                <p class="font-medium">{{ $submission->problem->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Language') }}</p>
                                <p class="font-medium">{{ $submission->getLanguageName() }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Submitted At') }}</p>
                                <p class="font-medium">{{ $submission->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Status') }}</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $submission->status === 'Accepted' ? 'bg-green-100 text-green-800' : 
                                        ($submission->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $submission->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2">{{ __('Source Code') }}</h3>
                        <div class="bg-gray-50 p-4 rounded-md shadow-inner overflow-x-auto">
                            <pre class="text-sm font-mono whitespace-pre-wrap">{{ $submission->code }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 