<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Lyrics') }}
            </h2>
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('lyrics.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                    Upload New Lyrics
                </a>
            @endif
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
                                            <img class="protected-img w-full h-64 object-cover rounded" src="{{ Storage::url($lyric->image_path) }}" 
                                                 alt="Lyrics for {{ $lyric->title }} by {{ $lyric->artist }}" 
                                                 class="w-full h-48 object-cover lyrics-fullscreen-img">
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                            {{ $lyric->title }}
                                        </h3>
                                        <p class="text-gray-700 mb-2">{{ $lyric->artist }}</p>
                                        <p class="text-sm text-gray-600 mb-4">{{ $lyric->category }}</p>
                                        <!-- DEBUG: Is Admin? {{ (auth()->check() && auth()->user()->isAdmin()) ? 'True' : 'False' }} -->
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('lyrics.show', $lyric) }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                                    View Lyrics
                                                </a>
                                                <form action="{{ route('bookmarks.store', $lyric) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="inline-flex items-center p-2 text-red-500 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                            
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