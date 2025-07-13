<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Recent Bookmarks -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Bookmarks</h3>
                            @if($bookmarkedLyrics->isEmpty())
                                <p class="text-gray-500">You haven't bookmarked any lyrics yet.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($bookmarkedLyrics as $bookmark)
                                        <div class="flex items-center space-x-4">
                                            @if($bookmark->lyrics->image_path)
                                                <img src="{{ Storage::url($bookmark->lyrics->image_path) }}" 
                                                     alt="{{ $bookmark->lyrics->title }}" 
                                                     class="w-16 h-16 object-cover rounded">
                                            @endif
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    {{ $bookmark->lyrics->title }}
                                                </h4>
                                                <p class="text-sm text-gray-500">{{ $bookmark->lyrics->artist }}</p>
                                            </div>
                                            <a href="{{ route('lyrics.show', $bookmark->lyrics) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                View
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('bookmarks.index') }}" 
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        View all bookmarks →
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Recent Song Requests -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Song Requests</h3>
                            @if($recentSongRequests->isEmpty())
                                <p class="text-gray-500">You haven't requested any songs yet.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($recentSongRequests as $request)
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    {{ $request->title }}
                                                </h4>
                                                <p class="text-sm text-gray-500">{{ $request->artist }}</p>
                                                <p class="text-xs text-gray-400">
                                                    Requested {{ $request->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($request->status === 'pending')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif($request->status === 'approved')
                                                    bg-green-100 text-green-800
                                                @else
                                                    bg-red-100 text-red-800
                                                @endif
                                            ">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('song-requests.index') }}" 
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        View all requests →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('lyrics.index') }}" 
                               class="flex items-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                                <div class="p-2 bg-indigo-100 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Browse Lyrics (ဃုတၢ်သးဝံၣ်)</h4>
                                    <p class="text-sm text-gray-500">Find your favorite songs</p>
                                </div>
                            </a>

                            <a href="{{ route('song-requests.create') }}" 
                               class="flex items-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Request a Song (ဃ့ထီၣ်တၢ်သးဝံၣ်)</h4>
                                    <p class="text-sm text-gray-500">Can't find what you're looking for?</p>
                                </div>
                            </a>

                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Edit Profile</h4>
                                    <p class="text-sm text-gray-500">Update your information</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<head>
    <title>Karen Song Lyrics - Find and Share Karen Lyrics</title>
    <meta name="description" content="Discover, search, and share Karen song lyrics. The best place for Karen music fans!">
    <meta name="keywords" content="Karen, song, lyrics, Karen songs, Karen music, Karen lyrics">
    <!-- ...existing head content... -->
</head> 