@section('title', 'Karen Song Lyrics | Lyrics App')
@section('meta')
    <meta name="description" content="Discover and explore Karen song lyrics. Find lyrics for your favorite Karen songs, artists, and more!" />
    <meta name="keywords" content="Karen song lyrics, Karen songs, Karen music, Karen lyrics, ethnic music, lyrics app" />
@endsection

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to Lyrics App</h1>
                        <p class="text-lg text-gray-600 mb-6">Discover and explore song lyrics from your favorite artists</p>
                        @guest
                            <div class="flex justify-center space-x-4">
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Register
                                </a>
                            </div>
                        @endguest
                    </div>

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

                    <!-- Latest Lyrics -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Latest Lyrics</h2>
                        @if($lyrics->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500">No lyrics found.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($lyrics as $lyric)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                        @if($lyric->thumbnail_path)
                                            <div class="aspect-w-16 aspect-h-9">
                                                <img src="{{ Storage::url($lyric->thumbnail_path) }}" 
                                                     alt="Thumbnail for {{ $lyric->title }} by {{ $lyric->artist }}" 
                                                     class="w-full h-48 object-cover lyrics-fullscreen-img">
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
    </div>
</x-app-layout>
