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
                    <form action="{{ route('lyrics.index') }}" method="GET" class="mb-8">
                        <div class="flex gap-4">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search lyrics..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Search
                            </button>
                        </div>
                    </form>

                    <!-- Latest Lyrics -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Latest Lyrics</h2>
                        @if($lyrics->isEmpty())
                            <p class="text-gray-600">No lyrics found.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($lyrics as $lyric)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                        @if($lyric->image_path)
                                            <img src="{{ Storage::url($lyric->image_path) }}" alt="{{ $lyric->title }}" class="w-full h-48 object-cover">
                                        @else
                                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-400">No image</span>
                                            </div>
                                        @endif
                                        <div class="p-4">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $lyric->title }}</h3>
                                            <p class="text-gray-600 mb-4">{{ $lyric->artist }}</p>
                                            <a href="{{ route('lyrics.show', $lyric) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                                View Lyrics
                                            </a>
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
