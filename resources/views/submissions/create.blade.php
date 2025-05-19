<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Submit Solution') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('submissions.store') }}">
                        @csrf

                        <!-- Problem Selection -->
                        <div class="mb-4">
                            <x-input-label for="problem_id" :value="__('Problem')" />
                            <select id="problem_id" name="problem_id" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select a problem</option>
                                @foreach ($problems as $problem)
                                    <option value="{{ $problem->id }}" {{ old('problem_id') == $problem->id ? 'selected' : '' }}>
                                        {{ $problem->title }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('problem_id')" class="mt-2" />
                        </div>

                        <!-- Language Selection -->
                        <div class="mb-4">
                            <x-input-label for="language_id" :value="__('Programming Language')" />
                            <select id="language_id" name="language_id" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select a language</option>
                                @foreach ($languages as $language)
                                    <option value="{{ $language['id'] }}" {{ old('language_id') == $language['id'] ? 'selected' : '' }}>
                                        {{ $language['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('language_id')" class="mt-2" />
                        </div>

                        <!-- Code -->
                        <div class="mb-4">
                            <x-input-label for="code" :value="__('Your Code')" />
                            <textarea id="code" name="code" rows="15" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 font-mono"
                            >{{ old('code') }}</textarea>
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Submit Solution') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 