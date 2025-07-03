<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Lyrics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('lyrics.update', $lyric) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $lyric->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="artist" :value="__('Artist')" />
                            <x-text-input id="artist" name="artist" type="text" class="mt-1 block w-full" :value="old('artist', $lyric->artist)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('artist')" />
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Lyrics Image')" />
                            @if($lyric->image_path)
                                <div class="mb-2">
                                    <img class="protected-img" src="{{ Storage::url($lyric->image_path) }}" alt="Current lyrics image" class="w-32 h-32 object-cover rounded">
                                </div>
                            @endif
                            <input type="file" id="image" name="image" class="mt-1 block w-full" accept="image/*">
                            <p class="mt-1 text-sm text-gray-500">Upload a new image of the lyrics (max 5MB). Leave empty to keep the current image.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div>
                            <x-input-label for="about" :value="__('About These Lyrics (optional)')" />
                            <textarea id="about" name="about" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="5">{{ old('about', $lyric->about) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('about')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Lyrics') }}</x-primary-button>
                            <a href="{{ route('lyrics.show', $lyric) }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 