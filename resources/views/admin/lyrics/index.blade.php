<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Lyrics (Admin)') }}
            </h2>
            <a href="{{ route('lyrics.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                Upload New Lyrics
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Search Form -->
                    <form action="{{ route('lyrics.index') }}" method="GET" class="mb-6">
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <x-text-input type="text" name="search" placeholder="Search lyrics..." class="w-full" value="{{ request('search') }}" />
                            </div>
                            <div>
                                <x-primary-button type="submit" class="bg-indigo-600 hover:bg-indigo-700">Search</x-primary-button>
                            </div>
                        </div>
                    </form>

                    @if($lyrics->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500">No lyrics found.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($lyrics as $lyric)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                    @if($lyric->image_path)
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img class="protected-img w-32 h-32 object-cover rounded" src="{{ Storage::url($lyric->image_path) }}" 
                                                 alt="Lyrics for {{ $lyric->title }} by {{ $lyric->artist }}" 
                                                 class="w-full h-48 object-cover">
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                            {{ $lyric->title }}
                                        </h3>
                                        <p class="text-gray-700 mb-2">{{ $lyric->artist }}</p>
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('lyrics.show', $lyric) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                                View Lyrics
                                            </a>
                                            
                                            @if(auth()->check() && auth()->user()->isAdmin())
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('lyrics.edit', $lyric) }}" 
                                                       class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition ease-in-out duration-150">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('lyrics.destroy', $lyric) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150"
                                                                onclick="return confirm('Are you sure you want to delete this lyrics?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $lyrics->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 