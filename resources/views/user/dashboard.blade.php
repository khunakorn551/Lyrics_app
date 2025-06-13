<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Welcome, {{ Auth::user()->name }}!</h3>
                    
                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <a href="{{ route('lyrics.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-150 ease-in-out">
                            Browse Lyrics
                        </a>
                        <a href="{{ route('song-requests.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-150 ease-in-out">
                            Request a Song
                        </a>
                        <a href="{{ route('bookmarks.index') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-150 ease-in-out">
                            My Bookmarks
                        </a>
                        <a href="{{ route('profile.edit') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-150 ease-in-out">
                            Edit Profile
                        </a>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('lyrics.index') }}" class="mb-6">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <x-input-label for="search" :value="__('Search Lyrics')" />
                                <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" placeholder="Search by title or artist..." />
                            </div>
                            <div class="flex items-end">
                                <x-primary-button>{{ __('Search') }}</x-primary-button>
                            </div>
                        </div>
                    </form>

                    <!-- Recent Lyrics -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold mb-4">Recent Lyrics</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach(\App\Models\Lyrics::latest()->take(8)->get() as $lyric)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                    @if($lyric->image_path)
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img src="{{ Storage::url($lyric->image_path) }}" 
                                                 alt="{{ $lyric->title }}" 
                                                 class="w-full h-48 object-cover">
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h5 class="font-bold text-lg mb-2">{{ $lyric->title }}</h5>
                                        <p class="text-gray-600 text-sm mb-2">By {{ $lyric->artist }}</p>
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('lyrics.show', $lyric) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                View Lyrics
                                            </a>
                                            <form action="{{ route('bookmarks.store', $lyric) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- My Recent Bookmarks -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold mb-4">My Recent Bookmarks</h4>
                        @if($bookmarkedLyrics->isEmpty())
                            <p class="text-gray-500">You haven't bookmarked any lyrics yet.</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                @foreach($bookmarkedLyrics as $bookmark)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                        @if($bookmark->lyrics->image_path)
                                            <div class="aspect-w-16 aspect-h-9">
                                                <img src="{{ Storage::url($bookmark->lyrics->image_path) }}" 
                                                     alt="{{ $bookmark->lyrics->title }}" 
                                                     class="w-full h-48 object-cover">
                                            </div>
                                        @endif
                                        <div class="p-4">
                                            <h5 class="font-bold text-lg mb-2">{{ $bookmark->lyrics->title }}</h5>
                                            <p class="text-gray-600 text-sm mb-2">By {{ $bookmark->lyrics->artist }}</p>
                                            <div class="flex justify-between items-center">
                                                <a href="{{ route('lyrics.show', $bookmark->lyrics) }}" 
                                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    View Lyrics
                                                </a>
                                                <form action="{{ route('bookmarks.destroy', $bookmark->lyrics) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-right">
                                <a href="{{ route('bookmarks.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View All Bookmarks &rarr;</a>
                            </div>
                        @endif
                    </div>

                    <!-- My Recent Song Requests -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold mb-4">My Recent Song Requests</h4>
                        @if($recentSongRequests->isEmpty())
                            <p class="text-gray-500">You haven't made any song requests yet.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artist</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentSongRequests as $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $request->title }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $request->artist }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                        {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                        {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                                        {{ ucfirst($request->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $request->created_at->diffForHumans() }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-right">
                                <a href="{{ route('song-requests.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View All Requests &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 