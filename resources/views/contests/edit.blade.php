<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Contest: {{ $contest->title }}
        </h2>
    </x-slot>

    <x-contest-navigation :contest="$contest" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('contests.update', $contest) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" value="Title" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" 
                                :value="old('title', $contest->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="4">{{ old('description', $contest->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="start_time" value="Start Time" />
                                <x-text-input id="start_time" name="start_time" type="datetime-local" 
                                    class="mt-1 block w-full" :value="old('start_time', $contest->start_time->format('Y-m-d\TH:i'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                            </div>

                            <div>
                                <x-input-label for="end_time" value="End Time" />
                                <x-text-input id="end_time" name="end_time" type="datetime-local" 
                                    class="mt-1 block w-full" :value="old('end_time', $contest->end_time->format('Y-m-d\TH:i'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="password" value="New Password (leave blank to keep current)" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>

                        <div>
                            <x-input-label for="problems" value="Problems" />
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($problems as $problem)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="problems[]" value="{{ $problem->id }}"
                                            {{ in_array($problem->id, $selectedProblems) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ms-2">{{ $problem->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('problems')" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                Update Contest
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 