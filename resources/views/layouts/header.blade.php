@isset($header)
    <header class="bg-white shadow mb-4">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 lg:px-0 flex items-center gap-4">
            <img src="{{ asset('images/my-logo.png') }}" alt="Logo" class="w-8 h-8">
            <h1 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-2">{{ $header }}</h1>
        </div>
    </header>
@endisset 