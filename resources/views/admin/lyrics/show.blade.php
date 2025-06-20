<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $lyric->title }} - {{ $lyric->artist }} ({{ (auth()->check() && auth()->user()->isAdmin()) ? 'Admin View' : 'User View' }})
            </h2>
            @if(auth()->check() && auth()->user()->isAdmin())
                <div class="flex space-x-4">
                    <a href="{{ route('lyrics.edit', $lyric) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Edit
                    </a>
                    <form action="{{ route('lyrics.destroy', $lyric) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lyrics?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                            Delete
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col items-center">
                    @if($lyric->image_path)
                        <div class="w-full max-w-2xl mb-8">
                            <img src="{{ Storage::url($lyric->image_path) }}"
                                alt="Lyrics for {{ $lyric->title }} by {{ $lyric->artist }}"
                                class="w-full h-auto rounded-lg shadow-lg object-contain"
                                style="max-height: 600px;">
                        </div>
                    @else
                        <p class="text-gray-500 mb-8">No image uploaded for this lyric.</p>
                    @endif
                    <div class="w-full max-w-xl bg-gray-50 rounded-lg p-6 mb-4">
                        <div class="text-sm text-gray-500">
                            Uploaded by {{ $lyric->user->name }} on {{ $lyric->created_at->format('F j, Y') }}
                        </div>
                        <div class="mt-4 text-right">
                            <a href="{{ route('lyrics.index') }}" class="text-gray-600 hover:text-gray-900">Back to Lyrics</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
