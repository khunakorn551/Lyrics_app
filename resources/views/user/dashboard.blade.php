<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 p-6">
                <h3 class="text-2xl font-bold mb-6">Welcome, {{ Auth::user()->name }}!</h3>
                <!-- Quick Actions -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <a href="{{ route('lyrics.index') }}" class="flex flex-col items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold py-4 px-2 rounded-lg text-center transition duration-150 ease-in-out">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Browse Lyrics
                    </a>
                    <a href="{{ route('song-requests.create') }}" class="flex flex-col items-center bg-green-50 hover:bg-green-100 text-green-700 font-semibold py-4 px-2 rounded-lg text-center transition duration-150 ease-in-out">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Request a Song
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center bg-purple-50 hover:bg-purple-100 text-purple-700 font-semibold py-4 px-2 rounded-lg text-center transition duration-150 ease-in-out">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Edit Profile
                    </a>
                </div>
                <!-- Recent Bookmarks and Song Requests -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold mb-4">Recent Bookmarks</h4>
                        @if($bookmarkedLyrics->isEmpty())
                            <div class="flex flex-col items-center text-gray-400">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                                <span>No bookmarks yet.</span>
                            </div>
                        @else
                            <div class="space-y-2">
                                @foreach($bookmarkedLyrics as $bookmark)
                                    <div class="flex items-center space-x-3">
                                        @if($bookmark->lyrics->image_path)
                                            <img class="protected-img w-16 h-16 object-cover rounded" src="{{ Storage::url($bookmark->lyrics->image_path) }}" alt="{{ $bookmark->lyrics->title }}" />
                                        @endif
                                        <div class="flex-1">
                                            <div class="font-semibold">{{ $bookmark->lyrics->title }}</div>
                                            <div class="text-xs text-gray-500">By {{ $bookmark->lyrics->artist }}</div>
                                        </div>
                                        <a href="{{ route('lyrics.show', $bookmark->lyrics) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-right">
                                <a href="{{ route('bookmarks.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View All Bookmarks &rarr;</a>
                            </div>
                        @endif
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <h4 class="text-lg font-semibold mb-4">Recent Song Requests</h4>
                        @if($recentSongRequests->isEmpty())
                            <div class="flex flex-col items-center text-gray-400">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <span>No song requests yet.</span>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artist</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentSongRequests as $request)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm">{{ $request->title }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm">{{ $request->artist }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                        {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                        {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                                        {{ ucfirst($request->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at->diffForHumans() }}</td>
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
                <!-- Recent Lyrics -->
                <div class="mt-8">
                    <h4 class="text-lg font-semibold mb-4 border-b border-gray-200 pb-2">Recent Lyrics</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach(\App\Models\Lyrics::latest()->take(8)->get() as $lyric)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                @if($lyric->image_path)
                                    <img class="protected-img w-full h-64 object-cover rounded" src="{{ Storage::url($lyric->image_path) }}" alt="{{ $lyric->title }}" />
                                @endif
                                <div class="p-4">
                                    <h5 class="font-bold text-lg mb-2">{{ $lyric->title }}</h5>
                                    <p class="text-gray-600 text-sm mb-2">By {{ $lyric->artist }}</p>
                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('lyrics.show', $lyric) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Lyrics</a>
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
            </div>
        </div>
    </div>
</x-app-layout> 