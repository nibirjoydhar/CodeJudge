<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Comments for Problem') }}: {{ $problem->title }}
            </h2>
            <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800">
                &larr; Back to Problem
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <!-- New Comment Form -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Post a Comment</h3>
                        
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('comments.store', $problem) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Your Comment</label>
                                <textarea 
                                    id="content" 
                                    name="content" 
                                    rows="4" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Enter your comment here..."
                                    required
                                    maxlength="1000"
                                >{{ old('content') }}</textarea>
                                
                                @error('content')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                
                                <p class="text-sm text-gray-500 mt-1">Maximum 1000 characters</p>
                            </div>
                            
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Post Comment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Comments List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Comments ({{ $comments->count() }})</h3>
                    
                    @if($comments->isEmpty())
                        <p class="text-gray-500">No comments yet. Be the first to comment!</p>
                    @else
                        <div class="space-y-6">
                            @foreach($comments as $comment)
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center">
                                            <div class="font-medium text-gray-900">{{ $comment->user->name }}</div>
                                            <div class="ml-4 text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2 text-sm text-gray-700 whitespace-pre-line">
                                        {{ $comment->content }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 