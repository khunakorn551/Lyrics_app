<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $lyric->title }} - {{ $lyric->artist }}
            </h2>
            <div class="flex space-x-4">
                @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('lyrics.edit', $lyric) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Edit
                    </a>
                    <form action="{{ route('lyrics.destroy', $lyric) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500" onclick="return confirm('Are you sure you want to delete this lyrics?')">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 flex flex-col items-center">
                    @if($lyric->image_path)
                        <div class="w-full max-w-2xl mb-8">
                            <img src="{{ Storage::url($lyric->image_path) }}"
                                 alt="Lyrics for {{ $lyric->title }} by {{ $lyric->artist }}"
                                 class="w-full h-auto rounded-lg shadow-lg object-contain lyrics-fullscreen-img"
                                 style="max-height: 600px;">
                        </div>
                    @else
                        <p class="text-gray-500 mb-8">No image uploaded for this lyric.</p>
                    @endif
                    <div class="w-full max-w-xl bg-gray-50 rounded-lg p-6 mb-4">
                        <div class="text-sm text-gray-500">
                            Uploaded by {{ $lyric->user->name }} on {{ $lyric->created_at->format('F j, Y') }}
                        </div>
                    </div>
                    
                    <!-- Original Content Section (for SEO & Copyright) -->
                    @if($lyric->user && $lyric->user->isAdmin() && $lyric->about)
                        <div class="w-full max-w-xl bg-gray-100 rounded-lg p-6 mb-8 text-gray-700">
                            <h3 class="text-lg font-semibold mb-2">About These Lyrics:</h3>
                            <p>{!! nl2br(e($lyric->about)) !!}</p>
                        </div>
                    @endif

                    <!-- Comments Section -->
                    <div class="w-full max-w-xl bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-6">Comments ({{ $lyric->comments->count() }})</h3>

                        <!-- New Comment Form -->
                        <form action="{{ route('comments.store', $lyric) }}" method="POST" class="mb-8">
                            @csrf
                            <textarea name="content" rows="3" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('content') border-red-500 @enderror" placeholder="Write your comment..."></textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div class="mt-3 flex justify-end">
                                <x-primary-button>{{ __('Post Comment') }}</x-primary-button>
                            </div>
                        </form>

                        <!-- Existing Comments -->
                        @forelse($lyric->comments()->whereNull('parent_id')->with(['user', 'replies.user', 'likes'])->latest()->get() as $comment)
                            <div class="mb-6 p-4 border rounded-lg bg-gray-50">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-semibold text-gray-800">{{ $comment->user->name }} <span class="text-gray-500 text-sm font-normal">{{ $comment->created_at->diffForHumans() }}</span></div>
                                    <div class="flex items-center space-x-2">
                                        <!-- Like/Unlike Button -->
                                        <form action="{{ $comment->likes()->where('user_id', Auth::id())->exists() ? route('comments.unlike', $comment) : route('comments.like', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="flex items-center text-red-500 hover:text-red-700 transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                                                    fill="{{ $comment->likes()->where('user_id', Auth::id())->exists() ? 'currentColor' : 'none' }}"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                                <span>{{ $comment->likes_count }}</span>
                                            </button>
                                        </form>

                                        <!-- Admin Actions -->
                                        @if(auth()->check() && auth()->user()->isAdmin())
                                            <button onclick="toggleReplyForm('{{ $comment->id }}')" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Reply</button>
                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-gray-700 mb-4">{{ $comment->content }}</p>

                                <!-- Reply Form (Admin Only) -->
                                @if(auth()->check() && auth()->user()->isAdmin())
                                    <div id="reply-form-{{ $comment->id }}" class="hidden mt-4 pl-4 border-l-2 border-blue-200">
                                        <form action="{{ route('comments.store', $lyric) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <textarea name="content" rows="2" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('content') border-red-500 @enderror" placeholder="Reply to this comment..."></textarea>
                                            @error('content')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                            <div class="mt-2 flex justify-end">
                                                <x-primary-button>Reply</x-primary-button>
                                                <button type="button" onclick="toggleReplyForm('{{ $comment->id }}')" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                <!-- Replies -->
                                @foreach($comment->replies as $reply)
                                    <div class="mt-4 pl-4 border-l-2 border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="font-semibold text-gray-800">{{ $reply->user->name }} <span class="text-gray-500 text-sm font-normal">{{ $reply->created_at->diffForHumans() }}</span></div>
                                            <div class="flex items-center space-x-2">
                                                <!-- Like/Unlike Button for Reply -->
                                                <form action="{{ $reply->likes()->where('user_id', Auth::id())->exists() ? route('comments.unlike', $reply) : route('comments.like', $reply) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="flex items-center text-red-500 hover:text-red-700 transition-colors duration-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                                                            fill="{{ $reply->likes()->where('user_id', Auth::id())->exists() ? 'currentColor' : 'none' }}"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                        <span>{{ $reply->likes_count }}</span>
                                                    </button>
                                                </form>

                                                @if((auth()->check() && auth()->user()->isAdmin()) || Auth::id() === $reply->user_id)
                                                    <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Are you sure you want to delete this reply?')">Delete</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-gray-700">{{ $reply->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <p class="text-gray-500">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleReplyForm(commentId) {
            const form = document.getElementById(`reply-form-${commentId}`);
            form.classList.toggle('hidden');
        }
    </script>

    @if(auth()->check())
        <!-- Report Modal -->
        <div id="reportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Report Lyrics</h3>
                    <form action="{{ route('lyrics.report', $lyric) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="mt-2">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Report</label>
                            <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                        </div>
                        <div class="mt-4 flex justify-end space-x-3">
                            <button type="button" onclick="document.getElementById('reportModal').classList.add('hidden')" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-transparent rounded-md hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-red-500">
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif


</x-app-layout> 