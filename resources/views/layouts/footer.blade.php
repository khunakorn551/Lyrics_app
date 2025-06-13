<footer class="bg-white border-t mt-8">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center text-gray-600 text-sm">
        <div class="mb-4 md:mb-0">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
        <div class="space-x-4">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-800">Dashboard</a>
            <a href="{{ route('lyrics.index') }}" class="hover:text-gray-800">Lyrics</a>
            @auth
                <a href="{{ route('profile.edit') }}" class="hover:text-gray-800">Profile</a>
            @endauth
            <a href="{{ route('about') }}" class="hover:text-gray-800">About Us</a>
            <a href="{{ route('contact') }}" class="hover:text-gray-800">Contact Us</a>
            <a href="{{ route('privacy') }}" class="hover:text-gray-800">Privacy Policy</a>
            <a href="{{ route('terms') }}" class="hover:text-gray-800">Terms of Service</a>
        </div>
    </div>
</footer>
