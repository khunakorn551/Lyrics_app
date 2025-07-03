<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Bookmarks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex gap-4">
                    <a href="{{ route('lyrics.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload New Lyrics</a>
                    <a href="{{ route('lyrics.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Manage Lyrics</a>
                </div>
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Bookmarked Lyrics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse($bookmarks as $bookmark)
                            @if($bookmark->lyrics)
                                <div class="border rounded p-2 flex flex-col items-center">
                                    @if($bookmark->lyrics->thumbnail_path)
                                        <div class="w-full aspect-w-16 aspect-h-9 mb-2">
                                            <img src="{{ Storage::url($bookmark->lyrics->thumbnail_path) }}" 
                                                 alt="{{ $bookmark->lyrics->title }} thumbnail" 
                                                 class="w-full h-32 object-cover rounded" />
                                        </div>
                                    @elseif($bookmark->lyrics->image_path)
                                        <div class="w-full aspect-w-16 aspect-h-9 mb-2">
                                            <img src="{{ Storage::url($bookmark->lyrics->image_path) }}" 
                                                 alt="{{ $bookmark->lyrics->title }}" 
                                                 class="w-full h-32 object-cover rounded" />
                                        </div>
                                    @endif
                                    <div class="font-bold">{{ $bookmark->lyrics->title }}</div>
                                    <div class="text-xs text-gray-500 mb-1">By {{ $bookmark->lyrics->artist }}</div>
                                    <a href="{{ route('lyrics.show', $bookmark->lyrics) }}" class="text-blue-600 hover:underline text-xs">View</a>
                                </div>
                            @endif
                        @empty
                            <div class="text-gray-500">You have no bookmarks yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 